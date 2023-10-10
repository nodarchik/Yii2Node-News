import { Router } from 'express';
import { login, logout, validateToken } from '../controllers/authController';
import { authMiddleware } from '../middleware/authMiddleware';

const router = Router();

router.post('/login', login);
router.post('/logout', authMiddleware, logout);
router.post('/validate-token', authMiddleware, validateToken);

export default router;
