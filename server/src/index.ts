import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';

import { authRouter } from './routes/auth.js';
import { complaintsRouter } from './routes/complaints.js';
import { capRouter } from './routes/cap.js';
import { travelRouter } from './routes/travel.js';
import { riskRouter } from './routes/risk.js';
import { policyRouter } from './routes/policy.js';
import { pdpRouter } from './routes/pdp.js';
import { trainingRouter } from './routes/training.js';
import { statesRouter } from './routes/states.js';
import { lessonsRouter } from './routes/lessons.js';
import { investigationRouter } from './routes/investigation.js';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 5000;

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

app.use(cors({ origin: '*' }));
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ extended: true, limit: '50mb' }));
app.use('/uploads', express.static(path.join(__dirname, '../uploads')));

// Mount Endpoints for all 15 modules
app.use('/api/auth', authRouter);
app.use('/api/complaints', complaintsRouter);
app.use('/api/cap', capRouter);
app.use('/api/travel', travelRouter);
app.use('/api/risk', riskRouter);
app.use('/api/policy', policyRouter);
app.use('/api/pdp', pdpRouter);
app.use('/api/training', trainingRouter);
app.use('/api/states', statesRouter);
app.use('/api/lessons', lessonsRouter);
app.use('/api/investigation', investigationRouter);

app.get('/api/health', (_req, res) => {
  res.json({
    status: 'online',
    system: 'CCCRN ComplianceIQ API Server',
    environment: 'production-ready',
    timestamp: new Date().toISOString()
  });
});

app.listen(PORT, () => {
  console.log('🚀 CCCRN Compliance API Server listening on port ' + PORT);
  console.log('📡 Health endpoint: http://localhost:' + PORT + '/api/health');
});
