import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest } from '../middleware/auth.js';

export const statesRouter = Router();

statesRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const states = await prisma.stateProfile.findMany({
      include: { fieldUpdates: true },
      orderBy: { compliance: 'desc' }
    });
    res.json(states);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
