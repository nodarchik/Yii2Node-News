import { MongoClient } from 'mongodb';

export const connectDB = async () => {
    const client = new MongoClient(process.env.MONGO_URI!);
    await client.connect();
    return client.db('andersen');
};
