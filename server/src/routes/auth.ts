import { Router, Request, Response } from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import { prisma } from '../prisma.js';

export const authRouter = Router();
const JWT_SECRET = process.env.JWT_SECRET || 'cccrn_compliance_secret_jwt_key_2026_super_secure';

// 1. LOGIN
authRouter.post('/login', async (req: Request, res: Response) => {
  try {
    const { email, password, roleKey } = req.body;

    let user;
    if (roleKey) {
      user = await prisma.user.findFirst({ where: { roleKey } });
    } else if (email) {
      user = await prisma.user.findUnique({ where: { email } });
    }

    if (!user) {
      return res.status(404).json({ error: 'User not found in institutional directory' });
    }

    const token = jwt.sign(
      { id: user.id, email: user.email, roleKey: user.roleKey, name: user.name },
      JWT_SECRET,
      { expiresIn: '7d' }
    );

    res.json({
      token,
      user: {
        id: user.id,
        email: user.email,
        name: user.name,
        roleKey: user.roleKey,
        roleBadge: user.roleBadge,
        avatar: user.avatar,
        department: user.department,
        state: user.state
      }
    });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});

// 2. REGISTER
authRouter.post('/register', async (req: Request, res: Response) => {
  try {
    const { name, email, password, department, state, roleKey } = req.body;

    if (!email || !name) {
      return res.status(400).json({ error: 'Name and email are required' });
    }

    // Check if user already exists
    const existing = await prisma.user.findUnique({ where: { email } });
    if (existing) {
      return res.status(400).json({ error: 'An account with this email address already exists' });
    }

    const passwordHash = await bcrypt.hash(password || 'Compliance2026!', 10);
    const assignedRoleKey = roleKey || 'staff';
    const roleBadge = assignedRoleKey === 'compliance_officer'
      ? 'COMPLIANCE SPECIALIST'
      : assignedRoleKey === 'hr'
      ? 'HR ACCESS'
      : assignedRoleKey === 'doc'
      ? 'ADMIN (DoC)'
      : 'STAFF ACCESS';

    const avatar = name.split(' ').map((n: string) => n[0]).join('').substring(0, 2).toUpperCase() || 'ST';

    const newUser = await prisma.user.create({
      data: {
        name,
        email: email.toLowerCase().trim(),
        passwordHash,
        roleKey: assignedRoleKey,
        roleBadge,
        avatar,
        department: department || 'Programme Delivery',
        state: state || 'Lagos'
      }
    });

    const token = jwt.sign(
      { id: newUser.id, email: newUser.email, roleKey: newUser.roleKey, name: newUser.name },
      JWT_SECRET,
      { expiresIn: '7d' }
    );

    res.status(201).json({
      token,
      user: {
        id: newUser.id,
        email: newUser.email,
        name: newUser.name,
        roleKey: newUser.roleKey,
        roleBadge: newUser.roleBadge,
        avatar: newUser.avatar,
        department: newUser.department,
        state: newUser.state
      }
    });
  } catch (err: any) {
    res.status(500).json({ error: err.message });
  }
});
