import { MongoClient } from 'mongodb';
import { createUser, readUser, updateUser, deleteUser } from '../models/userModel';

export const registerUser = async (db: MongoClient, userData: any) => {
    return await createUser(db, userData);
};

export const readUserById = async (db: MongoClient, userId: string) => {
    return await readUser(db, userId);
};

export const updateUserById = async (db: MongoClient, userId: string, updateData: any) => {
    return await updateUser(db, userId, updateData);
};

export const deleteUserById = async (db: MongoClient, userId: string) => {
    return await deleteUser(db, userId);
};
