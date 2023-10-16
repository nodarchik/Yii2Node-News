import { Request, Response, NextFunction } from 'express';
import { StatusCodes } from 'http-status-codes';

interface ErrorResponse {
    status: number;
    message: string;
}

export const errorHandler = (error: any, req: Request, res: Response, next: NextFunction) => {
    let responseError: ErrorResponse;

    if (error.name === 'ValidationError') {
        responseError = {
            status: StatusCodes.BAD_REQUEST,
            message: error.message,
        };
    } else if (error.name === 'MongoError' && error.code === 11000) {
        responseError = {
            status: StatusCodes.CONFLICT,
            message: 'Duplicate key error',
        };
    } else {
        responseError = {
            status: error.status || StatusCodes.INTERNAL_SERVER_ERROR,
            message: error.message || 'Internal Server Error',
        };
    }

    res.status(responseError.status).json({
        error: responseError.message,
    });
};
