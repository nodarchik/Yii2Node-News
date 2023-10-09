import jwt from 'jsonwebtoken';

export const auth = (req: any, res: any, next: any) => {
    const token = req.header('Authorization');

    if (req.path === '/api/register' || req.path === '/api/users/register') {
        return next();
    }

    if (!token) {
        return res.status(401).send('Access denied. No token provided.');
    }

    try {
        req.user = jwt.verify(token, process.env.JWT_SECRET!);
        next();
    } catch (ex) {
        res.status(400).send('Invalid token.');
    }
};
