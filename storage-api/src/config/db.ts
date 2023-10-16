import mongoose from 'mongoose';
import { config } from './config';

export const connectDB = async (): Promise<void> => {
    try {
        await mongoose.connect(config.mongoURI);
        console.log('MongoDB Connected...');
    } catch (error) {
        console.error('DB Connection Error: ', (error as Error).message);
        // Graceful shutdown in case of a database connection error
        process.exit(1);
    }
};
