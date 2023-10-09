import { MongoClient } from 'mongodb';

export const connectDB = async () => {
    const client = new MongoClient('mongodb://localhost:27017');
    await client.connect();
    return client;
};
