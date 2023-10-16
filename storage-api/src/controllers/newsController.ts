import { Request, Response } from 'express';
import { NewsService } from '../services/newsService';
import { StatusCodes } from 'http-status-codes';
import { validationResult } from 'express-validator';

export class NewsController {
    static async createNews(req: Request, res: Response) {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(StatusCodes.BAD_REQUEST).json({ errors: errors.array() });
        }
        try {
            const { title, content } = req.body;
            const news = await NewsService.createNews(title, content);
            res.status(StatusCodes.CREATED).json({ data: news, message: 'News created successfully' });
        } catch (error: any) {
            res.status(StatusCodes.BAD_REQUEST).json({ error: error.message });
        }
    }

    static async getAllNews(req: Request, res: Response) {
        try {
            const news = await NewsService.getAllNews();
            res.status(StatusCodes.OK).json({ data: news });
        } catch (error: any) {
            res.status(StatusCodes.INTERNAL_SERVER_ERROR).json({ error: 'Failed to fetch news' });
        }
    }

    static async getNewsById(req: Request, res: Response) {
        try {
            const { id } = req.params;
            const newsItem = await NewsService.getNewsById(id);
            if (!newsItem) {
                return res.status(StatusCodes.NOT_FOUND).json({ error: 'News not found' });
            }
            res.status(StatusCodes.OK).json({ data: newsItem });
        } catch (error: any) {
            res.status(StatusCodes.INTERNAL_SERVER_ERROR).json({ error: 'Failed to fetch news' });
        }
    }

    static async updateNews(req: Request, res: Response) {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(StatusCodes.BAD_REQUEST).json({ errors: errors.array() });
        }
        try {
            const { id } = req.params;
            const { title, content } = req.body;
            const updatedNews = await NewsService.updateNews(id, title, content);
            if (!updatedNews) {
                return res.status(StatusCodes.NOT_FOUND).json({ error: 'News not found' });
            }
            res.status(StatusCodes.OK).json({ data: updatedNews, message: 'News updated successfully' });
        } catch (error: any) {
            res.status(StatusCodes.INTERNAL_SERVER_ERROR).json({ error: 'Failed to update news' });
        }
    }

    static async deleteNews(req: Request, res: Response) {
        try {
            const { id } = req.params;
            const deletedNews = await NewsService.deleteNews(id);
            if (!deletedNews) {
                return res.status(StatusCodes.NOT_FOUND).json({ error: 'News not found' });
            }
            res.status(StatusCodes.OK).json({ message: 'News deleted successfully' });
        } catch (error: any) {
            res.status(StatusCodes.INTERNAL_SERVER_ERROR).json({ error: 'Failed to delete news' });
        }
    }
}
