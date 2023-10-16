import {NextFunction, Request, Response} from 'express';
import jwt from 'jsonwebtoken';
import {config} from '../config/config';
import { AuthService } from '../services/authService';

export const authMiddleware = (req: Request, res: Response, next: NextFunction) => {
    const token = req.header('Authorization');
    if (!token) return res.status(401).send('Access denied. No token provided.');

    if (AuthService.blacklistedTokens[token]) {
        return res.status(401).send('Token is blacklisted.');
    }

    try {
        (req as any).user = jwt.verify(token, config.JWT_SECRET);
        next();
    } catch (ex) {
        res.status(400).send('Invalid token.');
    }
};
