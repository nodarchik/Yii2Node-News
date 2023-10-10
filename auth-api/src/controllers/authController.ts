import { Request, Response } from 'express';
import jwt from 'jsonwebtoken';
import { axiosInstance } from '../utils/axiosInstance';

export const login = async (req: Request, res: Response) => {
    try {
        // Assuming users authenticate against the Storage API
        const { data } = await axiosInstance.post('/users/login', req.body);
        const token = jwt.sign({ _id: data._id }, process.env.JWT_SECRET!, {
            expiresIn: '1h',
        });
        res.header('Authorization', token).status(200).send({ token });
    } catch (error) {
        res.status(500).send('Failed to login');
    }
};

export const logout = (req: Request, res: Response) => {
    // Implement logout logic, like invalidating the token if necessary
    res.status(200).send('Logged out successfully');
};

export const validateToken = (req: Request, res: Response) => {
    res.status(200).send({ valid: true });
};
