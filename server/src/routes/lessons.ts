import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest } from '../middleware/auth.js';

export const lessonsRouter = Router();

lessonsRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const lessons = await prisma.lessonLearned.findMany({
      orderBy: { createdAt: 'desc' }
    });
    res.json(lessons);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
