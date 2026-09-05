import { Request, Response, NextFunction } from 'express';
import jwt from 'jsonwebtoken';

export interface AuthRequest extends Request {
  user?: {
    id: string;
    email: string;
    roleKey: string;
    name: string;
  };
}

const JWT_SECRET = process.env.JWT_SECRET || 'cccrn_compliance_secret_jwt_key_2026_super_secure';

export const authenticate = (req: AuthRequest, res: Response, next: NextFunction) => {
  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    // Also allow x-user-role header for dev mode convenience
    const devRole = req.headers['x-user-role'] as string;
    const devEmail = req.headers['x-user-email'] as string;
    if (devRole) {
      req.user = {
        id: 'dev-' + devRole,
        email: devEmail || `${devRole}@cccrn.org`,
        roleKey: devRole,
        name: devRole.toUpperCase()
      };
      return next();
    }
    return res.status(401).json({ error: 'Authentication required' });
  }

  const token = authHeader.split(' ')[1];
  try {
    const decoded = jwt.verify(token, JWT_SECRET) as any;
    req.user = decoded;
    next();
  } catch (err) {
    return res.status(401).json({ error: 'Invalid or expired token' });
  }
};

export const requireRole = (allowedRoles: string[]) => {
  return (req: AuthRequest, res: Response, next: NextFunction) => {
    if (!req.user || !allowedRoles.includes(req.user.roleKey)) {
      return res.status(403).json({ error: 'Access restricted: Insufficient role permissions' });
    }
    next();
  };
};

export const requireDocOnly = requireRole(['doc']);
