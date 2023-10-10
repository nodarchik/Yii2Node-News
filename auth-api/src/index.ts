import express from 'express';
import { config } from 'dotenv';
import authRoutes from './routes/authRoutes';

config();

const app = express();
app.use(express.json());
app.use('/auth', authRoutes);

app.listen(3001, () => {
    console.log('Auth API is running on http://localhost:3001');
});
