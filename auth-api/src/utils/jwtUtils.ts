import jwt from 'jsonwebtoken';
import { config } from '../config/config';

export const generateToken = (userId: string): string => {
    return jwt.sign({ _id: userId }, config.JWT_SECRET, { expiresIn: '1h' });
};

export const verifyToken = (token: string): boolean => {
    try {
        jwt.verify(token, config.JWT_SECRET);
        return true;
    } catch {
        return false;
    }
};
