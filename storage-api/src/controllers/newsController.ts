import { MongoClient } from 'mongodb';
import { createNews, readNews, updateNews, deleteNews } from '../models/newsModel';

export const addNews = async (db: MongoClient, newsData: any) => {
    return await createNews(db, newsData);
};

export const readNewsById = async (db: MongoClient, newsId: string) => {
    return await readNews(db, newsId);
};

export const updateNewsById = async (db: MongoClient, newsId: string, updateData: any) => {
    return await updateNews(db, newsId, updateData);
};

export const deleteNewsById = async (db: MongoClient, newsId: string) => {
    return await deleteNews(db, newsId);
};
