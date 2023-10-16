import { Router } from 'express';
import { UserController } from '../../controllers/userController';

const router = Router();

router.post('/register', UserController.register);
router.get('/', UserController.getAllUsers);
router.put('/:id', UserController.updateUser);
router.delete('/:id', UserController.deleteUser);

export default router;
