import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest } from '../middleware/auth.js';

export const policyRouter = Router();

policyRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const policies = await prisma.policy.findMany({
      include: { acknowledgements: true },
      orderBy: { createdAt: 'desc' }
    });
    res.json(policies);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

policyRouter.post('/:id/acknowledge', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    const userEmail = req.user?.email || 'staff@cccrn.org';
    const userName = req.user?.name || 'Staff User';

    const ack = await prisma.policyAcknowledgement.create({
      data: {
        policyId: id,
        userEmail,
        userName
      }
    });

    res.status(201).json(ack);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
