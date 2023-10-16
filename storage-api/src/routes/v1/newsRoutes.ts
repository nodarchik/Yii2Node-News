import { Router } from 'express';
import { NewsController } from '../../controllers/newsController';

const router = Router();

router.post('/', NewsController.createNews);
router.get('/', NewsController.getAllNews);
router.get('/:id', NewsController.getNewsById);
router.put('/:id', NewsController.updateNews);
router.delete('/:id', NewsController.deleteNews);

export default router;
