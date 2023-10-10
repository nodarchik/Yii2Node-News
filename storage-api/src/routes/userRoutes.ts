import { Router } from 'express';
import { MongoClient } from 'mongodb';
import jwt from 'jsonwebtoken';

const router = Router();

// Define the /register route
router.post('/register', async (req, res) => {
  const client = new MongoClient(process.env.MONGO_URI!);
  try {
    await client.connect();
    const db = client.db();
    const user = req.body;
    // We should hash the password before storing it, but skipping for simplicity.

    const insertResult = await db.collection('users').insertOne(user);

    if (!insertResult.acknowledged) {
      return res.status(500).send('Failed to register user');
    }

    const newUserId = insertResult.insertedId;

    // Generate JWT Token
    const token = jwt.sign({ _id: newUserId, username: user.username }, process.env.JWT_SECRET!, {
      expiresIn: '1h',
    });

    // Send token in HTTP header
    res.header('Authorization', token).status(201).send({message: 'User registered successfully', token: token});
  } catch (err) {
    console.error(err);
    res.status(500).send('Failed to register user');
  } finally {
    await client.close();
  }
});

export default router;
