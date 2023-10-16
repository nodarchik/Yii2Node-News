import mongoose from 'mongoose';
import { MongoMemoryServer } from 'mongodb-memory-server';
import { NewsService } from '../services/newsService';
import { News } from '../models/News';
import { Types } from 'mongoose';

describe('NewsService', () => {
    let mongoServer: MongoMemoryServer;

    beforeAll(async () => {
        mongoServer = new MongoMemoryServer();
        const mongoUri = await mongoServer.getUri();
        await mongoose.connect(mongoUri, {
            useNewUrlParser: true,
            useUnifiedTopology: true,
            useFindAndModify: false,
            useCreateIndex: true
        } as mongoose.ConnectOptions);  // Cast as ConnectOptions to satisfy TypeScript
    });

    afterEach(async () => {
        await News.deleteMany({});
    });

    afterAll(async () => {
        await mongoose.connection.close();
        await mongoServer.stop();
    });

    it('should create news', async () => {
        const news = await NewsService.createNews('Test Title', 'Test Content');
        expect(news.title).toBe('Test Title');
        expect(news.content).toBe('Test Content');
    });

    it('should get all news', async () => {
        await NewsService.createNews('Test Title 1', 'Test Content 1');
        await NewsService.createNews('Test Title 2', 'Test Content 2');
        const newsItems = await NewsService.getAllNews();
        expect(newsItems.length).toBe(2);
    });

    it('should update a news item', async () => {
        const news = await NewsService.createNews('Test Title', 'Test Content');
        const updatedNews = await NewsService.updateNews(news._id.toString(), 'Updated Title', 'Updated Content');
        expect(updatedNews).not.toBeNull();
        if (updatedNews) {  // Null check
            expect(updatedNews.title).toBe('Updated Title');
            expect(updatedNews.content).toBe('Updated Content');
        }
    });

    it('should throw an error if news not found on update', async () => {
        await expect(NewsService.updateNews(new Types.ObjectId().toString(), 'Updated Title', 'Updated Content'))
            .rejects
            .toThrow('News not found');
    });

    it('should delete a news item', async () => {
        const news = await NewsService.createNews('Test Title', 'Test Content');
        const deletedNews = await NewsService.deleteNews(news._id.toString());
        expect(deletedNews).not.toBeNull();
        if (deletedNews) {  // Null check
            expect(deletedNews._id.toString()).toEqual(news._id.toString());
        }
    });

    it('should throw an error if news not found on delete', async () => {
        await expect(NewsService.deleteNews(new Types.ObjectId().toString()))
            .rejects
            .toThrow('News not found');
    });
});
