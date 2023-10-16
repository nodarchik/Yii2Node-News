import { Request, Response } from 'express';
import { AuthServiceInterface } from '../interfaces/authServiceInterface';
import { StatusCodes } from 'http-status-codes';

export class AuthController {
    static login = async (authService: AuthServiceInterface, req: Request, res: Response) => {
        try {
            const token = await authService.login(req.body.email, req.body.password);
            res.header('Authorization', token).status(StatusCodes.OK).send({ token });
        } catch (error: unknown) {  // Specify error type as unknown
            if (error instanceof Error) {  // Type guard to narrow down the type
                res.status(StatusCodes.INTERNAL_SERVER_ERROR).send(error.message);
            } else {
                res.status(StatusCodes.INTERNAL_SERVER_ERROR).send('An unknown error occurred');
            }
        }
    };

    static logout = (authService: AuthServiceInterface, req: Request, res: Response) => {
        const token = req.header('Authorization')!;
        authService.logout(token);
        res.status(StatusCodes.OK).send('Logged out successfully');
    };

    static validateToken = (authService: AuthServiceInterface, req: Request, res: Response) => {
        const token = req.header('Authorization')!;
        const isValid = authService.validateToken(token);
        res.status(StatusCodes.OK).send({ valid: isValid });
    };
}
