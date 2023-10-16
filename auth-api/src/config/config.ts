import { config as dotenvConfig } from 'dotenv';
dotenvConfig();

export const config = {
    JWT_SECRET: process.env.JWT_SECRET!,
    STORAGE_API_URL: process.env.STORAGE_API_URL!,
};
