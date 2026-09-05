import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest, requireDocOnly } from '../middleware/auth.js';

export const complaintsRouter = Router();

complaintsRouter.get('/', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const userRole = req.user?.roleKey;
    const userEmail = req.user?.email;

    let complaints;
    if (userRole === 'staff') {
      complaints = await prisma.complaint.findMany({
        where: { loggedByEmail: userEmail },
        orderBy: { createdAt: 'desc' }
      });
    } else {
      complaints = await prisma.complaint.findMany({
        orderBy: { createdAt: 'desc' }
      });
    }

    res.json(complaints);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

complaintsRouter.post('/', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { state, category, severity, source, allegedParty, summary } = req.body;
    const count = await prisma.complaint.count();
    const newId = 'CMP-0' + (49 + count);
    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    const newComplaint = await prisma.complaint.create({
      data: {
        id: newId,
        date: today,
        state: state || 'Lagos',
        category: category || 'General',
        severity: severity || 'Medium',
        source: source || 'Whistleblower',
        allegedParty: allegedParty || '—',
        summary: summary || '',
        status: 'Open',
        loggedByEmail: req.user?.email
      }
    });

    res.status(201).json(newComplaint);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

complaintsRouter.put('/:id/status', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    const { status } = req.body;

    const updated = await prisma.complaint.update({
      where: { id },
      data: { status }
    });

    res.json(updated);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

complaintsRouter.delete('/:id', authenticate, requireDocOnly, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;
    await prisma.complaint.delete({ where: { id } });
    res.json({ success: true, message: 'Complaint deleted' });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
