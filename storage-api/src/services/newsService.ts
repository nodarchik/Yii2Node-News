import { News, INews } from '../models/News';
import { Error } from 'mongoose';

export class NewsService {
    static async createNews(title: string, content: string): Promise<INews> {
        if (!title || !content) {
            throw new Error('Title and content are required');
        }

        const existingNews = await News.findOne({ title });
        if (existingNews) {
            throw new Error('News with this title already exists');
        }

        const news = new News({ title, content });
        await news.save();
        return news;
    }

    static async getAllNews(): Promise<INews[]> {
        return News.find();
    }

    static async getNewsById(id: string): Promise<INews | null> {
        if (!id) {
            throw new Error('ID is required');
        }

        const news = await News.findById(id);
        if (!news) {
            throw new Error('News not found');
        }

        return news;
    }

    static async updateNews(id: string, title?: string, content?: string): Promise<INews | null> {
        if (!id || (!title && !content)) {
            throw new Error('ID and either title or content are required');
        }

        const updateData: Partial<INews> = {};
        if (title) updateData.title = title;
        if (content) updateData.content = content;

        const updatedNews = await News.findByIdAndUpdate(id, updateData, { new: true });
        if (!updatedNews) {
            throw new Error('News not found');
        }

        return updatedNews;
    }

    static async deleteNews(id: string): Promise<INews | null> {
        if (!id) {
            throw new Error('ID is required');
        }

        const deletedNews = await News.findByIdAndDelete(id);
        if (!deletedNews) {
            throw new Error('News not found');
        }

        return deletedNews;
    }
}
