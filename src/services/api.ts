/**
 * CCCRN ComplianceIQ — Central API Client
 * Connects React frontend to Express + Prisma Backend (http://localhost:5000/api)
 */

const API_BASE_URL = 'http://localhost:5000/api';

class ApiClient {
  private token: string | null = null;
  private userRole: string = 'doc';

  constructor() {
    // Retrieve stored token or default
    this.token = localStorage.getItem('cccrn_auth_token');
    this.userRole = localStorage.getItem('cccrn_user_role') || 'doc';
  }

  public setAuth(token: string, role: string) {
    this.token = token;
    this.userRole = role;
    localStorage.setItem('cccrn_auth_token', token);
    localStorage.setItem('cccrn_user_role', role);
  }

  public clearAuth() {
    this.token = null;
    localStorage.removeItem('cccrn_auth_token');
  }

  private async request<T>(endpoint: string, options: RequestInit = {}): Promise<T> {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
      'x-user-role': this.userRole,
      ...(this.token ? { Authorization: `Bearer ${this.token}` } : {}),
      ...(options.headers as Record<string, string> || {})
    };

    try {
      const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        ...options,
        headers
      });

      if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        throw new Error(errorData.error || `API request failed with status ${response.status}`);
      }

      return await response.json();
    } catch (err) {
      console.warn(`[API Service] Request to ${endpoint} failed, falling back to local state:`, err);
      throw err;
    }
  }

  // --- Auth ---
  public async login(roleKey: string) {
    return this.request<{ token: string; user: any }>('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ roleKey })
    });
  }

  // --- Complaints ---
  public async getComplaints() {
    return this.request<any[]>('/complaints');
  }

  public async createComplaint(data: any) {
    return this.request<any>('/complaints', {
      method: 'POST',
      body: JSON.stringify(data)
    });
  }

  public async updateComplaintStatus(id: string, status: string) {
    return this.request<any>(`/complaints/${id}/status`, {
      method: 'PUT',
      body: JSON.stringify({ status })
    });
  }

  public async deleteComplaint(id: string) {
    return this.request<{ success: boolean }>(`/complaints/${id}`, {
      method: 'DELETE'
    });
  }

  // --- CAP ---
  public async getCaps() {
    return this.request<any[]>('/cap');
  }

  public async createCap(data: any) {
    return this.request<any>('/cap', {
      method: 'POST',
      body: JSON.stringify(data)
    });
  }

  public async submitCapEvidence(id: string, notes: string, fileName?: string) {
    return this.request<any>(`/cap/${id}/evidence`, {
      method: 'POST',
      body: JSON.stringify({ notes, fileName })
    });
  }

  public async closeCap(id: string) {
    return this.request<any>(`/cap/${id}/close`, {
      method: 'PUT'
    });
  }

  public async deleteCap(id: string) {
    return this.request<{ success: boolean }>(`/cap/${id}`, {
      method: 'DELETE'
    });
  }

  // --- Travel ---
  public async getTravelData() {
    return this.request<{ requests: any[]; tickets: any[]; payments: any[] }>('/travel');
  }

  public async createTravelRequest(data: any) {
    return this.request<any>('/travel/request', {
      method: 'POST',
      body: JSON.stringify(data)
    });
  }

  public async issueTicket(data: any) {
    return this.request<any>('/travel/issue-ticket', {
      method: 'POST',
      body: JSON.stringify(data)
    });
  }

  public async uploadBoardingPass(ticketId: string, fileName: string) {
    return this.request<any>('/travel/upload-boarding-pass', {
      method: 'POST',
      body: JSON.stringify({ ticketId, fileName })
    });
  }

  public async clearVendorPayment(paymentId: string) {
    return this.request<any>(`/travel/clear-payment/${paymentId}`, {
      method: 'PUT'
    });
  }

  // --- Risk ---
  public async getRisks() {
    return this.request<any[]>('/risk');
  }

  public async createRisk(data: any) {
    return this.request<any>('/risk', {
      method: 'POST',
      body: JSON.stringify(data)
    });
  }

  public async deleteRisk(id: string) {
    return this.request<{ success: boolean }>(`/risk/${id}`, {
      method: 'DELETE'
    });
  }
}

export const api = new ApiClient();
