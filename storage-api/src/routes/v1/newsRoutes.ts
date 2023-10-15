import { Router } from 'express';
import { connectDB } from '../config/db';
import { News } from '../models/News';
import { ObjectId } from 'mongodb';

const router = Router();

router.post('/', async (req, res) => {
    const db = await connectDB();
    const news: News = req.body;
    await db.collection('news').insertOne(news);
    res.status(201).send('News added');
});

router.get('/', async (req, res) => {
    const db = await connectDB();
    const news = await db.collection('news').find().toArray();
    res.status(200).json(news);
});

router.get('/:id', async (req, res) => {
    const db = await connectDB();
    const newsItem = await db.collection('news').findOne({ _id: new ObjectId(req.params.id) });
    if (!newsItem) return res.status(404).send('News not found');
    res.status(200).json(newsItem);
});

router.put('/:id', async (req, res) => {
    const db = await connectDB();
    const updatedNews = await db.collection('news').updateOne(
        { _id: new ObjectId(req.params.id) },
        { $set: req.body }
    );
    if (!updatedNews.matchedCount) return res.status(404).send('News not found');
    res.status(200).send('News updated');
});

router.delete('/:id', async (req, res) => {
    const db = await connectDB();
    const deletedNews = await db.collection('news').deleteOne({ _id: new ObjectId(req.params.id) });
    if (!deletedNews.deletedCount) return res.status(404).send('News not found');
    res.status(200).send('News deleted');
});

export default router;
