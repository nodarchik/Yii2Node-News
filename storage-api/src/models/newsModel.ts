import { MongoClient } from 'mongodb';

export const createNews = async (db: MongoClient, newsData: object) => {
    // Add your validation logic here
    const collection = db.db('your_database').collection('news');
    return await collection.insertOne(newsData);
};

export const readNews = async (db: MongoClient, newsId: string) => {
    const collection = db.db('your_database').collection('news');
    return await collection.findOne({ newsId });
};

export const updateNews = async (db: MongoClient, newsId: string, updateData: object) => {
    const collection = db.db('your_database').collection('news');
    return await collection.updateOne({ newsId }, { $set: updateData });
};

export const deleteNews = async (db: MongoClient, newsId: string) => {
    const collection = db.db('your_database').collection('news');
    return await collection.deleteOne({ newsId });
};
