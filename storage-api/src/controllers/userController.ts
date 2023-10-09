import { MongoClient } from 'mongodb';
import { createUser } from '../models/userModel';

export const registerUser = async (db: MongoClient, userData: object) => {
    return await createUser(db, userData);
};
