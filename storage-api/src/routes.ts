import { IncomingMessage, ServerResponse } from 'http';
import { MongoClient } from 'mongodb';
import { tokenMiddleware } from './middlewares/tokenMiddleware';
import { registerUser, readUser, updateUser, deleteUser } from './controllers/userController';
import { createNews, readNews, updateNews, deleteNews } from './controllers/newsController';

// Helper function to get POST data
const getPostData = (req: IncomingMessage): Promise<any> => {
    return new Promise((resolve, reject) => {
        let body = '';
        req.on('data', (chunk) => {
            body += chunk.toString();
        });
        req.on('end', () => {
            resolve(JSON.parse(body));
        });
    });
};

export const routeHandler = async (req: IncomingMessage, res: ServerResponse, db: MongoClient) => {
    const { method, url } = req;

    if (url === '/register' && method === 'POST') {
        const userData = await getPostData(req);
        await registerUser(db, userData);
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ message: 'User registered' }));
    } else {
        await tokenMiddleware(req, res, async () => {}).catch(err => console.error(err));
            if (url?.startsWith('/user')) {
                const urlParts = url.split('/');
                const userId = urlParts[2] || '';
                switch (method) {
                    case 'GET':
                        const user = await readUser(db, userId);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ user }));
                        break;
                    case 'PUT':
                        const updateUserData = await getPostData(req);
                        await updateUser(db, userId, updateUserData);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ message: 'User updated' }));
                        break;
                    case 'DELETE':
                        await deleteUser(db, userId);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ message: 'User deleted' }));
                        break;
                }
            } else if (url?.startsWith('/news')) {
                const urlParts = url.split('/');
                const newsId = urlParts[2] || '';
                switch (method) {
                    case 'POST':
                        const newsData = await getPostData(req);
                        await createNews(db, newsData);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ message: 'News created' }));
                        break;
                    case 'GET':
                        const news = await readNews(db, newsId);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ news }));
                        break;
                    case 'PUT':
                        const updateNewsData = await getPostData(req);
                        await updateNews(db, newsId, updateNewsData);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ message: 'News updated' }));
                        break;
                    case 'DELETE':
                        await deleteNews(db, newsId);
                        res.writeHead(200, { 'Content-Type': 'application/json' });
                        res.end(JSON.stringify({ message: 'News deleted' }));
                        break;
                }
            }
        });
    }
};
