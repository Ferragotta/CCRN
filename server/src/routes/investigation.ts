import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest, requireDocOnly } from '../middleware/auth.js';

export const investigationRouter = Router();

investigationRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const cases = await prisma.investigationCase.findMany({
      include: { rcaFindings: true, controlDeviations: true, evidenceLog: true },
      orderBy: { createdAt: 'desc' }
    });
    res.json(cases);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
