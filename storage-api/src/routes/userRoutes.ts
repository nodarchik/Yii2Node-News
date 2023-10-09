import { Router } from 'express';
import { connectDB } from '../config/db';
import { User } from '../models/User';
import { ObjectId } from 'mongodb';
import jwt from 'jsonwebtoken';

const router = Router();

router.post('/register', async (req, res) => {
    const db = await connectDB();
    const user: User = req.body;
    // We should hash the password before storing it, but skipping for simplicity.
    await db.collection('users').insertOne(user);

    // Generate JWT Token
    const token = jwt.sign({ _id: user._id, username: user.username }, process.env.JWT_SECRET!, {
        expiresIn: '1h',
    });

    // Send token in HTTP header
    res.header('Authorization', token).status(201).send('User registered with token');
});


router.get('/', async (req, res) => {
    const db = await connectDB();
    const users = await db.collection('users').find().toArray();
    res.status(200).json(users);
});

router.get('/:id', async (req, res) => {
    const db = await connectDB();
    const user = await db.collection('users').findOne({ _id: new ObjectId(req.params.id) });
    if (!user) return res.status(404).send('User not found');
    res.status(200).json(user);
});

router.put('/:id', async (req, res) => {
    const db = await connectDB();
    const updatedUser = await db.collection('users').updateOne(
        { _id: new ObjectId(req.params.id) },
        { $set: req.body }
    );
    if (!updatedUser.matchedCount) return res.status(404).send('User not found');
    res.status(200).send('User updated');
});

router.delete('/:id', async (req, res) => {
    const db = await connectDB();
    const deletedUser = await db.collection('users').deleteOne({ _id: new ObjectId(req.params.id) });
    if (!deletedUser.deletedCount) return res.status(404).send('User not found');
    res.status(200).send('User deleted');
});

export default router;
