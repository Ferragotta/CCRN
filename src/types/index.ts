export type RoleKey = 'doc' | 'compliance_officer' | 'hr' | 'staff';

export interface UserRoleConfig {
  key: RoleKey;
  name: string;
  roleBadge: string;
  avatar: string;
  email: string;
  allowedModules: string[];
  defaultModule: string;
}

export interface Complaint {
  id: string;
  date: string;
  state: string;
  category: string;
  severity: 'Critical' | 'High' | 'Medium' | 'Low';
  status: 'Open' | 'In Progress' | 'Closed' | 'Converted to CAP';
  source?: string;
  alleged?: string;
}

export interface CorrectiveActionPlan {
  id: string;
  issue: string;
  state: string;
  linkedComplaint?: string;
  responsibleLead: string;
  deadline: string;
  status: 'Open' | 'In Progress' | 'Evidence Submitted' | 'Verified';
  evidenceFile?: string | null;
}
