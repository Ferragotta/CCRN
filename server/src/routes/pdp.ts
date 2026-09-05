import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest } from '../middleware/auth.js';

export const pdpRouter = Router();

pdpRouter.get('/', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const userRole = req.user?.roleKey;
    const userEmail = req.user?.email;

    if (userRole === 'staff') {
      const pdp = await prisma.pDP.findUnique({
        where: { userEmail },
        include: { objectives: true, evidences: true, innovations: true, behaviourals: true }
      });
      return res.json(pdp ? [pdp] : []);
    }

    // HR and Supervisors see all PDPs
    const pdps = await prisma.pDP.findMany({
      include: { objectives: true, evidences: true, innovations: true, behaviourals: true },
      orderBy: { createdAt: 'desc' }
    });
    res.json(pdps);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
