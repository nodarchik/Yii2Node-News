import jwt from 'jsonwebtoken';
import { Request, Response, NextFunction } from 'express';
import { config } from '../config/config';
import { StatusCodes } from 'http-status-codes';
import { IUser } from '../models/User';

interface IReqUser extends Request {
    user?: any;
}

export const auth = async (req: IReqUser, res: Response, next: NextFunction) => {
    const token = req.header('Authorization')?.split(' ')[1];  // Expecting 'Bearer <token>'

    if (!token) {
        return res.status(StatusCodes.UNAUTHORIZED).json({ error: 'Access denied. No token provided.' });
    }

    try {
        const decodedToken = jwt.verify(token, config.jwtSecret) as IUser;
        req.user = decodedToken;
        next();
    } catch (ex) {
        res.status(StatusCodes.BAD_REQUEST).json({ error: 'Invalid token.' });
    }
};
