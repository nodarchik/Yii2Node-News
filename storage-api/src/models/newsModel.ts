import { MongoClient } from 'mongodb';

export const createNews = async (db: MongoClient, newsData: any) => {
    // Validate newsData before inserting
    if (!newsData.title || !newsData.content) {
        throw new Error("Missing title or content");
    }

    const collection = db.db('mongodb').collection('news');
    return await collection.insertOne(newsData);
};

export const readNews = async (db: MongoClient, newsId: string) => {
    const collection = db.db('mongodb').collection('news');
    return await collection.findOne({ newsId });
};

export const updateNews = async (db: MongoClient, newsId: string, updateData: any) => {
    const collection = db.db('mongodb').collection('news');
    return await collection.updateOne({ newsId }, { $set: updateData });
};

export const deleteNews = async (db: MongoClient, newsId: string) => {
    const collection = db.db('mongodb').collection('news');
    return await collection.deleteOne({ newsId });
};
