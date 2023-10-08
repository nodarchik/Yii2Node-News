import http from 'http';
import { sign, verify } from 'jsonwebtoken'; // make sure to install jsonwebtoken library

// Dummy function to generate JWT
const generateToken = (userId: string) => {
    return sign({ userId }, 'your-secret-key');
};

// Dummy function to validate JWT
const validateToken = (token: string) => {
    try {
        return verify(token, 'your-secret-key');
    } catch (err) {
        return null;
    }
};

// Create an HTTP server
const server = http.createServer((req, res) => {
    if (req.url === '/login') {
        const token = generateToken('someUserId');
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ token }));
    } else if (req.url === '/validate') {
        const token = 'someTokenFromClient';
        const user = validateToken(token);

        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ valid: !!user }));
    } else if (req.url === '/logout') {
        // Your logic to handle logout
        res.end('Logged out');
    } else {
        res.end('Unknown route');
    }
});

server.listen(3001, () => {
    console.log('Server running on http://localhost:3001/');
});
