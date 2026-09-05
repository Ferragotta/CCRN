import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest } from '../middleware/auth.js';

export const trainingRouter = Router();

trainingRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const modules = await prisma.trainingModule.findMany({
      include: { attendances: true },
      orderBy: { createdAt: 'desc' }
    });
    res.json(modules);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
