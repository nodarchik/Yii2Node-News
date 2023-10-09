import { MongoClient } from 'mongodb';
import { createNews } from '../models/newsModel';

export const addNews = async (db: MongoClient, newsData: object) => {
    return await createNews(db, newsData);
};
