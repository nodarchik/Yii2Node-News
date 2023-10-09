import { IncomingMessage, ServerResponse } from "http";
import { validateToken } from '../index';
export const tokenMiddleware = async (req: IncomingMessage, res: ServerResponse, next: Function) => {
    return new Promise(async (resolve, reject) => {
        // Skip middleware for registration
        if (req.url === '/register') {
            next();
            resolve();
            return;
        }

        const token = req.headers['authorization']?.split(' ')[1];  // Assuming the token is sent in an "Authorization: Bearer <token>" header

        if (!token) {
            res.writeHead(401, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ message: 'Missing token' }));
            reject('Missing token');
            return;
        }

        try {
            const isValid = await validateToken(token);
            if (isValid) {
                next();
                resolve();
            } else {
                res.writeHead(401, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ message: 'Invalid token' }));
                reject('Invalid token');
            }
        } catch (err) {
            res.writeHead(500, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ message: 'Internal server error' }));
            reject(err);
        }
    });
};
