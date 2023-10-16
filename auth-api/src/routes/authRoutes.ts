import { Router } from 'express';
import { AuthController } from '../controllers/authController';
import { AuthService } from '../services/authService';
import { authMiddleware } from '../middleware/authMiddleware';

const authService = new AuthService();
const router = Router();

router.post('/v1/login', (req, res) => AuthController.login(authService, req, res));
router.post('/v1/logout', authMiddleware, (req, res) => AuthController.logout(authService, req, res));
router.post('/v1/validate-token', authMiddleware, (req, res) => AuthController.validateToken(authService, req, res));

export default router;
