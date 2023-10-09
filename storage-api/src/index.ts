import http from 'http';
import { sign, verify, SignOptions, VerifyOptions } from 'jsonwebtoken';
import { MongoClient } from 'mongodb';

const SECRET_KEY = process.env.JWT_SECRET || 'your-secret-key'; // Should be from an environment variable

const generateToken = async (userId: string) => {
    const payload = { userId };
    const options: SignOptions = {
        expiresIn: '1h' // expires in 1 hour
    };

    return sign(payload, SECRET_KEY, options);
};

export const validateToken = async (token: string) => {
    const options: VerifyOptions = {
        algorithms: ['HS256']
    };

    try {
        return verify(token, SECRET_KEY, options);
    } catch (err) {
        return null;
    }
};

const server = http.createServer(async (req, res) => {
    if (req.url === '/login') {
        // In a real scenario, you would authenticate the user first.
        const token = await generateToken('someUserId');
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ token }));
    } else if (req.url === '/validate') {
        const token = 'someTokenFromClient'; // Usually from req.headers.authorization
        const user = await validateToken(token);

        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ valid: !!user }));
    } else if (req.url === '/logout') {
        // In a real scenario, you might invalidate the token.
        res.end('Logged out');
    } else {
        res.end('Unknown route');
    }
});

server.listen(3001, () => {
    console.log('Server running on http://localhost:3001/');
});
