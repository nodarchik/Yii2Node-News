import express from 'express';
import dotenv from 'dotenv';
import { auth } from './middleware/auth';
import userRoutes from './routes/userRoutes';
import newsRoutes from './routes/newsRoutes';
import { connectDB } from './config/db';

dotenv.config();

const app = express();

app.use(express.json());

// Routes that do not require authentication
app.use('/api/users', userRoutes);

// Authentication middleware
app.use(auth);

// Routes that require authentication
app.use('/api/news', newsRoutes);

const start = async () => {
    try {
        await connectDB();
        app.listen(3000, () => console.log('Server is running on port 3000'));
    } catch (error) {
        console.error("Failed to start the server", error);
    }
};

start().catch(error => {
    console.error("An error occurred while starting the application", error);
});

