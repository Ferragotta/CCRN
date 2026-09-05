import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest, requireDocOnly } from '../middleware/auth.js';

export const riskRouter = Router();

riskRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const risks = await prisma.riskItem.findMany({ orderBy: { rating: 'desc' } });
    res.json(risks);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

riskRouter.post('/', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { category, description, likelihood, impact, mitigation, owner } = req.body;
    const l = parseInt(likelihood) || 3;
    const i = parseInt(impact) || 3;
    const rating = l * i;
    const level = rating >= 15 ? 'Critical' : rating >= 10 ? 'High' : rating >= 5 ? 'Medium' : 'Low';
    const count = await prisma.riskItem.count();
    const newId = 'RSK-0' + (20 + count);

    const newRisk = await prisma.riskItem.create({
      data: {
        id: newId,
        category,
        description,
        likelihood: l,
        impact: i,
        rating,
        level,
        mitigation,
        owner,
        status: 'Active'
      }
    });

    res.status(201).json(newRisk);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

riskRouter.delete('/:id', authenticate, requireDocOnly, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    await prisma.riskItem.delete({ where: { id } });
    res.json({ success: true, message: 'Risk deleted' });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
