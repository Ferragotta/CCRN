import { Router, Response } from 'express';
import { prisma } from '../prisma.js';
import { authenticate, AuthRequest } from '../middleware/auth.js';

export const travelRouter = Router();

travelRouter.get('/', authenticate, async (_req: AuthRequest, res: Response) => {
  try {
    const requests = await prisma.travelRequest.findMany({
      include: { ticket: { include: { vendorPayment: true } } },
      orderBy: { createdAt: 'desc' }
    });
    const tickets = await prisma.ticketPurchase.findMany({
      include: { vendorPayment: true },
      orderBy: { createdAt: 'desc' }
    });
    const payments = await prisma.vendorPayment.findMany({
      orderBy: { createdAt: 'desc' }
    });

    res.json({ requests, tickets, payments });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

travelRouter.post('/request', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { travellerName, department, route, travelDate, returnDate, purpose, estimatedCost, donorCode, authDocName } = req.body;
    const count = await prisma.travelRequest.count();
    const newId = 'TR-' + (109 + count);
    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    const newReq = await prisma.travelRequest.create({
      data: {
        id: newId,
        travellerName: travellerName || req.user?.name || 'Staff User',
        department: department || 'Operations',
        route,
        travelDate,
        returnDate,
        purpose,
        estimatedCost: parseFloat(estimatedCost) || 0,
        donorCode: donorCode || 'DOS-2026-001',
        authDocName,
        requestDate: today,
        status: 'Pending Logistics',
        userEmail: req.user?.email
      }
    });

    res.status(201).json(newReq);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

travelRouter.post('/issue-ticket', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { reqRef, travellerName, route, airline, vendorName, ticketNumber, amount, travelDate, returnDate } = req.body;
    const count = await prisma.ticketPurchase.count();
    const newTktId = 'TKT-' + (555 + count);
    const payId = 'PAY-' + (92 + count);
    const today = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    const ticket = await prisma.ticketPurchase.create({
      data: {
        id: newTktId,
        reqRef,
        travellerName,
        route,
        airline,
        vendorName,
        ticketNumber,
        amount: parseFloat(amount) || 0,
        purchaseDate: today,
        travelDate,
        returnDate,
        status: 'Issued / Open',
        boardingPassUploaded: false,
        paymentStatus: 'Unpaid'
      }
    });

    await prisma.vendorPayment.create({
      data: {
        id: payId,
        vendorName,
        invoiceRef: 'INV-' + vendorName.substring(0, 3).toUpperCase() + '-2026-' + (count + 92),
        ticketId: newTktId,
        tickets: newTktId + ' (' + travellerName + ')',
        amount: parseFloat(amount) || 0,
        dueDate: '14 Days Net',
        status: 'Pending Boarding Pass',
        boardingPassVerified: false
      }
    });

    await prisma.travelRequest.update({
      where: { id: reqRef },
      data: { status: 'Ticket Issued' }
    });

    res.status(201).json(ticket);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

travelRouter.post('/upload-boarding-pass', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { ticketId, fileName } = req.body;

    const updatedTicket = await prisma.ticketPurchase.update({
      where: { id: ticketId },
      data: {
        status: 'Utilised',
        boardingPassUploaded: true,
        boardingPassFile: fileName || 'Boarding_Pass.pdf'
      }
    });

    await prisma.vendorPayment.update({
      where: { ticketId },
      data: {
        status: 'Ready for Payment',
        boardingPassVerified: true
      }
    });

    res.json(updatedTicket);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

travelRouter.put('/clear-payment/:id', authenticate, async (req: AuthRequest, res: Response) => {
  try {
    const { id } = req.params;

    const payment = await prisma.vendorPayment.findUnique({ where: { id } });
    if (!payment?.boardingPassVerified) {
      return res.status(400).json({ error: 'Compliance Block: Boarding pass not verified' });
    }

    const cleared = await prisma.vendorPayment.update({
      where: { id },
      data: { status: 'Paid' }
    });

    await prisma.ticketPurchase.update({
      where: { id: payment.ticketId },
      data: { paymentStatus: 'Paid' }
    });

    res.json(cleared);
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
