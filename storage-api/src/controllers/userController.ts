import { Request, Response } from 'express';
import { UserService } from '../services/userService';
import { StatusCodes } from 'http-status-codes';
import { validationResult } from 'express-validator';

export class UserController {
    static async register(req: Request, res: Response) {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(StatusCodes.UNPROCESSABLE_ENTITY).json({ errors: errors.array() });
        }
        try {
            const { username, password, confirmPassword } = req.body;
            const result = await UserService.register(username, password, confirmPassword);
            res.header('Authorization', result.token).status(StatusCodes.CREATED).send({
                message: 'User registered successfully',
                user: result.user,
                token: result.token,
            });
        } catch (error: unknown) {
            if (error instanceof Error) {
                res.status(StatusCodes.BAD_REQUEST).send({ error: error.message });
            } else {
                res.status(StatusCodes.INTERNAL_SERVER_ERROR).send({ error: 'An unknown error occurred' });
            }
        }

    }

    static async getAllUsers(req: Request, res: Response) {
        try {
            const users = await UserService.getAllUsers();
            res.status(StatusCodes.OK).send({ data: users });
        } catch (error) {
            res.status(StatusCodes.INTERNAL_SERVER_ERROR).send({ error: 'Failed to fetch users' });
        }
    }

    static async updateUser(req: Request, res: Response) {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(StatusCodes.UNPROCESSABLE_ENTITY).json({ errors: errors.array() });
        }
        try {
            const { id } = req.params;
            const { username, password } = req.body;
            const result = await UserService.updateUser(id, username, password);
            if (!result.user) return res.status(StatusCodes.NOT_FOUND).send({ error: 'User not found' });
            res.status(StatusCodes.OK).send({ data: result.user });
        } catch (error: unknown) {
            if (error instanceof Error) {
                res.status(StatusCodes.BAD_REQUEST).send({ error: error.message });
            } else {
                res.status(StatusCodes.INTERNAL_SERVER_ERROR).send({ error: 'An unknown error occurred' });
            }
        }

    }

    static async deleteUser(req: Request, res: Response) {
        try {
            const { id } = req.params;
            const result = await UserService.deleteUser(id);
            if (!result.user) return res.status(StatusCodes.NOT_FOUND).send({ error: 'User not found' });
            res.status(StatusCodes.OK).send({ message: 'User deleted', data: result.user });
        } catch (error: unknown) {
            if (error instanceof Error) {
                res.status(StatusCodes.BAD_REQUEST).send({ error: error.message });
            } else {
                res.status(StatusCodes.INTERNAL_SERVER_ERROR).send({ error: 'An unknown error occurred' });
            }
        }

    }
}
