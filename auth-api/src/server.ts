import axios from 'axios';
import { config } from 'dotenv';

config();

export const axiosInstance = axios.create({
    baseURL: process.env.STORAGE_API_URL,
    timeout: 5000,
});
