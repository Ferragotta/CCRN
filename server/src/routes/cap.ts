import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest, requireDocOnly } from '../middleware/auth.js';

export const capRouter = Router();

capRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const caps = await prisma.cAP.findMany({
      include: { evidences: true },
      orderBy: { createdAt: 'desc' }
    });
    res.json(caps);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

capRouter.post('/', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { issue, state, linkedRef, deadline, responsible, priority, rootCause } = req.body;
    const count = await prisma.cAP.count();
    const newId = 'CAP-0' + (33 + count);

    const newCap = await prisma.cAP.create({
      data: {
        id: newId,
        issue,
        state,
        linkedRef,
        deadline,
        responsible,
        priority: priority || 'High',
        rootCause,
        status: 'Open'
      }
    });

    res.status(201).json(newCap);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

capRouter.post('/:id/evidence', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    const { notes, fileName } = req.body;

    const evidence = await prisma.cAPEvidence.create({
      data: {
        capId: id,
        submitter: req.user?.name || 'Staff User',
        notes,
        fileName,
        status: 'Pending Review'
      }
    });

    await prisma.cAP.update({
      where: { id },
      data: { status: 'Pending Review' }
    });

    res.status(201).json(evidence);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

capRouter.put('/:id/close', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    const closed = await prisma.cAP.update({
      where: { id },
      data: { status: 'Closed' }
    });
    res.json(closed);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

capRouter.delete('/:id', authenticate, requireDocOnly, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    await prisma.cAP.delete({ where: { id } });
    res.json({ success: true, message: 'CAP deleted' });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
