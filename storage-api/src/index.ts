import express from 'express';
import { connectDB } from './config/db';
import userRoutes from './routes/v1/userRoutes';
import { errorHandler } from './middleware/errorHandler';
import newsRoutes from './routes/v1/newsRoutes';

const app = express();

app.use(express.json());

// Routes
app.use('/api/v1/users', userRoutes);
app.use('/api/v1/news', newsRoutes);

// Global error handler
app.use(errorHandler);

const start = async () => {
    await connectDB();
    app.listen(3000, () => console.log('Server is running on port 3000'));
};

start();
