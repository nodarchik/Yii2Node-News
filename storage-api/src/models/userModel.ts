import { MongoClient } from 'mongodb';

export const createUser = async (db: MongoClient, userData: object) => {
    // validate userData before inserting
    const collection = db.db('your_database').collection('users');
    return await collection.insertOne(userData);
};

export const readUser = async (db: MongoClient, userId: string) => {
    const collection = db.db('your_database').collection('users');
    return await collection.findOne({ userId });
};

export const updateUser = async (db: MongoClient, userId: string, updateData: object) => {
    const collection = db.db('your_database').collection('users');
    return await collection.updateOne({ userId }, { $set: updateData });
};

export const deleteUser = async (db: MongoClient, userId: string) => {
    const collection = db.db('your_database').collection('users');
    return await collection.deleteOne({ userId });
};
