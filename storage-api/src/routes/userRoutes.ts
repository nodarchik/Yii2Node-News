import { Router } from 'express';
import { MongoClient } from 'mongodb';
import jwt from 'jsonwebtoken';

const router = Router();

// Define the /register route
router.post('/register', async (req, res) => {
  const client = new MongoClient(process.env.MONGO_URI!);
  try {
    const { username, password, confirmPassword } = req.body;

    if (!username || !password) {
      return res.status(400).send('Username and password are required');
    }
    // Check that password and confirmPassword are the same
    if (password !== confirmPassword) {
      return res.status(400).send('Passwords do not match');
    }

    await client.connect();
    const db = client.db();

    // Rest of your existing code...

    const insertResult = await db.collection('users').insertOne({ username, password });

    if (!insertResult.acknowledged) {
      return res.status(500).send('Failed to register user');
    }

    const newUserId = insertResult.insertedId;

    // Generate JWT Token
    const token = jwt.sign({ _id: newUserId, username: username }, process.env.JWT_SECRET!, {
      expiresIn: '1h',
    });

    // Send token in HTTP header
    res.header('Authorization', token).status(201).send({ message: 'User registered successfully', token: token });
  } catch (err) {
    console.error(err);
    res.status(500).send('Failed to register user');
  } finally {
    await client.close();
  }
});

export default router;
