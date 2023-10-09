import { MongoClient } from 'mongodb';

export const createUser = async (db: MongoClient, userData: any) => {
    // Validate userData before inserting
    if (!userData.username || !userData.password) {
        throw new Error("Missing username or password");
    }

    const collection = db.db('mongodb').collection('users');
    return await collection.insertOne(userData);
};

export const readUser = async (db: MongoClient, userId: string) => {
    const collection = db.db('mongodb').collection('users');
    return await collection.findOne({ userId });
};

export const updateUser = async (db: MongoClient, userId: string, updateData: any) => {
    const collection = db.db('mongodb').collection('users');
    return await collection.updateOne({ userId }, { $set: updateData });
};

export const deleteUser = async (db: MongoClient, userId: string) => {
    const collection = db.db('mongodb').collection('users');
    return await collection.deleteOne({ userId });
};
