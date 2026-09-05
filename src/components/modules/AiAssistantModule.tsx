import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';

interface MessageItem {
  id: string;
  sender: 'ai' | 'user';
  text: string;
  timestamp: string;
}

export const AiAssistantModule: React.FC = () => {
  const { currentUser, isDocAdmin, setActiveModule } = useAuth();
  const { showInfo } = useToast();

  const isStaff = currentUser?.key === 'staff';
  const isHR = currentUser?.key === 'hr';
  const isComplianceOfficer = currentUser?.key === 'compliance_officer';

  // Mode: Director can switch between Director & Staff modes; Staff is strictly locked to Staff mode
  const [activeMode, setActiveMode] = useState<'director' | 'staff'>(isStaff ? 'staff' : 'director');

  // Staff Identity Strip (Optional parameters matching wireframe)
  const [staffName, setStaffName] = useState(currentUser?.name || '');
  const [staffState, setStaffState] = useState('');
  const [staffDonor, setStaffDonor] = useState('');

  // Director Messages
  const [directorMessages, setDirectorMessages] = useState<MessageItem[]>([
    {
      id: 'DM-1',
      sender: 'ai',
      text: '👋 Good day, Compliance Director.\n\nI have full visibility of your compliance data across all 6 operational states. I can help you:\n\n• Summarise open issues, complaints and CAPs by state\n• Analyse risk trends and flag urgent items\n• Draft donor reports, board briefings or management updates\n• Identify training gaps and follow-up priorities\n• Review lessons learned patterns\n\nUse the quick buttons above or type your question below.',
      timestamp: 'Just now'
    }
  ]);

  // Staff Helpdesk Messages (Exact matching wireframe)
  const [staffMessages, setStaffMessages] = useState<MessageItem[]>([
    {
      id: 'SM-1',
      sender: 'ai',
      text: '👋 **Hello! I\'m your Compliance Helpdesk Assistant.**\n\nI\'m here to help you navigate compliance questions — no question is too small. You can ask me things like:\n\n🔹 *"Is this type of expense allowable under USAID rules?"*\n🔹 *"Can we procure from a vendor who is related to our staff?"*\n🔹 *"What\'s the process for reporting a concern without giving my name?"*\n🔹 *"Our donor is FCDO — can we use funds for this activity?"*\n\nYou can optionally select your state and donor above so I can give you more specific guidance. Use the quick question buttons or type your question below.\n\n⚠️ *For urgent or serious concerns (fraud, abuse), please also report directly to the Compliance Unit.*',
      timestamp: 'Just now'
    }
  ]);

  const [inputVal, setInputVal] = useState('');
  const [isThinking, setIsThinking] = useState(false);

  // Guard: HR and Compliance Officer have No Access per user matrix
  if (isHR || isComplianceOfficer) {
    return (
      <div className="card" style={{ textAlign: 'center', padding: 40, background: 'var(--danger-light, #fee2e2)', border: '1px solid #fca5a5' }}>
        <i className="fa-solid fa-robot" style={{ fontSize: 32, color: 'var(--danger, #dc2626)', marginBottom: 12 }}></i>
        <h3 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 18, color: 'var(--danger, #dc2626)', marginBottom: 6 }}>
          Restricted Access: AI Assistance
        </h3>
        <p style={{ fontSize: 13, color: 'var(--text-dim)' }}>
          AI Assistant access is designated exclusively for Staff Helpdesk and Director of Compliance Intelligence.
        </p>
      </div>
    );
  }

  // Regulatory Knowledge Base Matcher matching wireframe
  const getAiResponse = (text: string, mode: 'director' | 'staff') => {
    const lower = text.toLowerCase();
    const donorPrefix = staffDonor ? '[Donor Context: ' + staffDonor + ']\n' : '';
    const statePrefix = staffState ? '[State: ' + staffState + ']\n' : '';

    if (mode === 'director') {
      if (lower.includes('kano')) {
        return '📍 **Kano State Compliance Dossier:**\n6 open issues. CAP-032 in progress. Training completion at 51% — below institutional target. Recommend direct engagement with Kano State Team Lead and Compliance Officer this week.';
      } else if (lower.includes('risk') || lower.includes('top risk')) {
        return '⚠️ **Top Risks Q1 2026 (ISO 31000):**\n\n🔴 **RSK-019**: Forex commodity volatility (Critical 20)\n🟠 **RSK-018**: Single signatory procurement bypass in Kano outreach (High 16)\n🟠 **RSK-017**: Unverified ad-hoc volunteer PSEA credentials (High 12)';
      } else if (lower.includes('donor') || lower.includes('report') || lower.includes('draft')) {
        return '📋 **Q1 2026 Statutory Donor Compliance Briefing:**\n\n• **Complaints:** 47 received, 29 (62%) resolved.\n• **CAPs:** 31 issued, 18 (58%) closed.\n• **Staff Training:** 64% (312/490 certified).\n• **Audit Gap:** Kaduna and Borno clusters require targeted supervision before COP close.';
      } else if (lower.includes('cap') || lower.includes('status')) {
        return '✅ **CAP Institutional Status:**\n\n76% overall completion rate across active CAPs. Highest performing states: Lagos (92%) and Rivers (94%). Action required for Borno (55%) regarding buffer supply logs.';
      } else if (lower.includes('training') || lower.includes('gap')) {
        return '🎓 **Training & Certification Gaps:**\n\n• Anti-Fraud SOP: 88% completed (On track)\n• PSEA Safeguarding: 64% completed (45 overdue staff in Kano & Kaduna)\n• 2 CFR 200 Procurement: Scheduled for March rollout.';
      } else if (lower.includes('lesson')) {
        return '💡 **Key Lessons Learned for Field Activities:**\n\n1. Digital mobile token authorization required for weekend outreach.\n2. Central HR background check before deploying volunteer nurses.\n3. Mandatory physical boarding pass upload before clearing airline invoices.';
      } else {
        return '📊 **Institutional Compliance Overview:**\n\nOverall compliance score is currently **Moderate (64%)**. Priority states for remediation: Kaduna (38%) and Borno (29%). Would you like me to draft a supervisory directive or export an audit briefing?';
      }
    } else {
      // STAFF HELPDESK RESPONSES (Exact matching original wireframe)
      if (lower.includes('fuel') || lower.includes('generator')) {
        return donorPrefix + statePrefix + '⛽ **Fuel/Generator Costs — 2 CFR 200**\n\n✅ **Generally Allowable** if:\n🔹 Included in your approved grant budget\n🔹 Operationally necessary (no reliable public grid power)\n🔹 Documented with authentic vendor receipts, log sheet, and written justification\n\n⚠️ *Under 2 CFR 200 §200.407, utility costs for certain unbudgeted facilities may need prior written approval — check your grant agreement.*\n\n**DoS / USAID:** Allowable if budgeted. **FCDO:** Allowable as office running cost with physical receipt logs.';
      } else if (lower.includes('gift') || lower.includes('facilitat') || lower.includes('bribe') || lower.includes('official')) {
        return donorPrefix + '🎁 **Gifts / Facilitation Payments to Officials**\n\n❌ **STRICTLY NOT ALLOWABLE — PROHIBITED**\n\n🔹 **2 CFR 200 §200.441**: Entertainment, gifts, and contributions are unallowable under federal awards.\n🔹 **US Foreign Corrupt Practices Act (FCPA)**: Strictly illegal to give anything of value to government officials.\n🔹 **UK Bribery Act 2010**: Criminal offence applying to FCDO/donor funds.\n🔹 **Nigeria Corrupt Practices Act (ICPC)**: Zero tolerance statutory offense.\n\n🚨 **Do not proceed.** Report any solicitation immediately to the Compliance Unit.';
      } else if (lower.includes('cash') || lower.includes('transfer') || lower.includes('beneficiary')) {
        return donorPrefix + '💵 **Cash Transfers & Beneficiary Payments — Compliance Rules**\n\n✅ **Allowable ONLY with Strict Documentation:**\n\n1. **Pre-Approved Activity Memo**: Must be signed by Project Director & Finance.\n2. **Beneficiary Verification**: Valid ID, signature/thumbprint, and phone number verification.\n3. **Dual-Custody Sign-Off**: At least 2 CCCRN staff members must witness and counter-sign the field payment register.\n4. **Daily Retirement**: Unspent cash advances must be returned to the bank within 24 hours of field return.';
      } else if (lower.includes('split') || lower.includes('invoice') || lower.includes('threshold')) {
        return donorPrefix + '🧾 **Invoice Splitting / Circumventing Thresholds**\n\n❌ **STRICTLY PROHIBITED — COMPLIANCE VIOLATION**\n\n🔹 **2 CFR 200 §200.318**: Artificially splitting purchases into smaller transactions to bypass procurement approval thresholds (e.g. 3-quote competitive bidding) is a major audit non-conformity.\n🔹 **Enforcement**: Invoices from the same vendor for related items must be aggregated.\n\n🚨 *If a vendor suggests splitting an invoice, refuse immediately and notify the Compliance Specialist.*';
      } else if (lower.includes('personal') || lower.includes('phone') || lower.includes('expense')) {
        return donorPrefix + '📱 **Personal Expenses vs Official Programme Funds**\n\n❌ **NOT ALLOWABLE**\n\n🔹 Donor funds can **never** be used for personal expenses (personal phone bills, unapproved personal travel, private vehicle repairs).\n🔹 Only official project communication allowances approved in the grant budget and backed by itemized call/data usage logs are eligible for reimbursement.';
      } else if (lower.includes('fraud') || lower.includes('suspect') || lower.includes('report') || lower.includes('whistleblower')) {
        return '🚨 **Reporting Suspected Fraud & Misconduct**\n\n• **Confidential Whistleblower Portal**: You can log a report directly under the **Complaints** module.\n• **Anonymity Protected**: You may choose to remain 100% anonymous.\n• **Anti-Retaliation Guarantee (POL-001)**: CCCRN strictly prohibits any adverse action or retaliation against any staff member who reports a compliance concern in good faith.\n• **Direct Hotline**: You may also email compliance@cccrn.org directly.';
      } else if (lower.includes('per diem') || lower.includes('subsistence') || lower.includes('allowance')) {
        return donorPrefix + '💰 **Per Diem & Daily Subsistence — 2 CFR 200 §200.474**\n\n✅ **Allowable** when:\n🔹 Rates comply with approved CCCRN travel policy and donor award ceilings.\n🔹 Trip purpose, official dates, destination, and signed attendance register are provided.\n⚠️ *Paying per diems above federal standard rates without prior approval creates a questioned cost in donor audits.*';
      } else if (lower.includes('procure') || lower.includes('purchase') || lower.includes('vendor')) {
        return donorPrefix + '🛒 **Procurement Rules — 2 CFR 200 §200.317–327**\n\n🔹 **Micro-Purchase (<$10,000 / ₦15M)**: Can be awarded without competitive quotes if price is reasonable.\n🔹 **Small Purchase ($10,000 – $250,000)**: Minimum of 3 competitive written price quotes required.\n🔹 **Sealed Bids / Proposals (>$250,000)**: Formal public bidding and technical evaluation committee mandatory.\n✅ Sole-Source is permitted **only** with prior written donor justification.';
      } else if (lower.includes('travel') || lower.includes('ticket') || lower.includes('flight') || lower.includes('boarding')) {
        return '✈️ **Travel Costs & Flight Compliance (2 CFR 200 §200.474)**\n\n✅ **Allowable** if in approved budget and travel authorization form is signed.\n🔹 **Mandatory Boarding Pass**: All staff must upload physical boarding pass images upon return under the **Travel** module to unlock vendor payment clearance.\n🔹 **US Funds**: Fly America Act mandates use of US flag carriers where applicable.';
      } else {
        return donorPrefix + statePrefix + '📋 **CCCRN Compliance Helpdesk Guidance**\n\nFor an accurate determination under 2 CFR 200 and institutional SOPs, please ask about a specific cost or situation:\n\n🔹 *"Is fuel for our generator allowable under DoS?"*\n🔹 *"What documentation is needed before paying cash to community volunteers?"*\n🔹 *"Can we accept gifts from a facility partner?"*\n🔹 *"What is the policy on invoice splitting?"*\n🔹 *"How do I upload my boarding pass after an official trip?"*';
      }
    }
  };

  const handleSendMessage = (textToSend?: string) => {
    const text = (textToSend || inputVal).trim();
    if (!text) return;

    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const userMsg: MessageItem = {
      id: 'MSG-' + Date.now(),
      sender: 'user',
      text,
      timestamp: time
    };

    if (activeMode === 'director') {
      setDirectorMessages(prev => [...prev, userMsg]);
    } else {
      setStaffMessages(prev => [...prev, userMsg]);
    }

    setInputVal('');
    setIsThinking(true);

    setTimeout(() => {
      const reply = getAiResponse(text, activeMode);
      const aiMsg: MessageItem = {
        id: 'AI-' + Date.now(),
        sender: 'ai',
        text: reply,
        timestamp: time
      };

      if (activeMode === 'director') {
        setDirectorMessages(prev => [...prev, aiMsg]);
      } else {
        setStaffMessages(prev => [...prev, aiMsg]);
      }
      setIsThinking(false);
    }, 450);
  };

  const handleClearChat = () => {
    if (activeMode === 'director') {
      setDirectorMessages([
        { id: 'DM-1', sender: 'ai', text: 'Chat cleared. Ask any executive compliance question.', timestamp: 'Just now' }
      ]);
    } else {
      setStaffMessages([
        { id: 'SM-1', sender: 'ai', text: 'Helpdesk chat cleared. Ask any compliance policy, allowable cost, or donor rule question.', timestamp: 'Just now' }
      ]);
    }
    showInfo('Chat Cleared', 'Conversation history reset.');
  };

  const activeMessages = activeMode === 'director' ? directorMessages : staffMessages;

  return (
    <div style={{ paddingBottom: 40 }}>
      {/* HEADER */}
      <div style={{ marginBottom: 16 }}>
        <h2 style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 20, fontWeight: 800, color: 'var(--text)' }}>
          AI Compliance Assistant & Staff Helpdesk
        </h2>
        <p style={{ fontSize: 12, color: 'var(--text-muted)', marginTop: 3 }}>
          Two modes: Director intelligence dashboard · Staff compliance helpdesk for any staff member across all states
        </p>

        {isStaff && (
          <div style={{ marginTop: 8, padding: '5px 12px', background: 'var(--accent-light)', color: 'var(--accent)', borderRadius: 6, fontSize: 11, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <i className="fa-solid fa-robot"></i> <strong>Staff Helpdesk Mode:</strong> Confidential guidance on 2 CFR 200, allowable costs, travel rules, and policies.
          </div>
        )}
      </div>

      {/* MODE SWITCHER (DoC Only — Staff is strictly locked to Staff Helpdesk) */}
      {isDocAdmin && (
        <div style={{ display: 'flex', gap: 12, marginBottom: 20 }}>
          <div
            onClick={() => setActiveMode('director')}
            style={{
              flex: 1,
              background: activeMode === 'director' ? 'var(--surface)' : 'var(--surface2)',
              border: activeMode === 'director' ? '2px solid var(--accent)' : '1px solid var(--border)',
              borderRadius: 'var(--radius-md)',
              padding: '14px 18px',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              boxShadow: activeMode === 'director' ? 'var(--shadow-sm)' : 'none'
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
              <div style={{ fontSize: 24 }}>🎯</div>
              <div>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 14, fontWeight: 700, color: 'var(--text)' }}>
                  Director Intelligence Mode
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  Executive analytics, state summaries, risk predictions, and donor report drafting
                </div>
              </div>
            </div>
            {activeMode === 'director' && (
              <span style={{ fontSize: 9, fontWeight: 800, background: 'var(--accent)', color: '#fff', padding: '2px 7px', borderRadius: 4 }}>
                ACTIVE
              </span>
            )}
          </div>

          <div
            onClick={() => setActiveMode('staff')}
            style={{
              flex: 1,
              background: activeMode === 'staff' ? 'var(--surface)' : 'var(--surface2)',
              border: activeMode === 'staff' ? '2px solid var(--success)' : '1px solid var(--border)',
              borderRadius: 'var(--radius-md)',
              padding: '14px 18px',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              boxShadow: activeMode === 'staff' ? 'var(--shadow-sm)' : 'none'
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
              <div style={{ fontSize: 24 }}>👥</div>
              <div>
                <div style={{ fontFamily: 'Plus Jakarta Sans', fontSize: 14, fontWeight: 700, color: 'var(--text)' }}>
                  Staff Helpdesk Mode
                </div>
                <div style={{ fontSize: 11, color: 'var(--text-muted)' }}>
                  Confidential helpdesk for staff inquiries on allowable costs, donor rules & whistleblower reporting
                </div>
              </div>
            </div>
            {activeMode === 'staff' && (
              <span style={{ fontSize: 9, fontWeight: 800, background: 'var(--success)', color: '#fff', padding: '2px 7px', borderRadius: 4 }}>
                ACTIVE
              </span>
            )}
          </div>
        </div>
      )}

      {/* STAFF HELPDESK BANNER (matching wireframe) */}
      {activeMode === 'staff' && (
        <div style={{
          background: 'linear-gradient(135deg, #e0f7fa, #e8f5e9)',
          border: '1px solid #b2dfdb',
          borderRadius: 12,
          padding: '14px 18px',
          marginBottom: 16,
          display: 'flex',
          alignItems: 'flex-start',
          gap: 12
        }}>
          <div style={{ fontSize: 24 }}>ℹ️</div>
          <div>
            <div style={{ fontWeight: 700, color: '#0077b6', fontSize: 13, marginBottom: 2 }}>
              Open to All Staff
            </div>
            <div style={{ fontSize: 12, color: '#2c5282', lineHeight: 1.5 }}>
              Any staff member — from any state or cluster — can use this helpdesk to ask compliance questions. Ask about whether a transaction is allowable, what a donor rule means, what to do in a situation, or how to handle a compliance concern. All conversations are confidential.
            </div>
          </div>
        </div>
      )}

      {/* CHAT CONTAINER CARD */}
      <div className="card">
        <div className="card-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <div style={{
              width: 34, height: 34, borderRadius: '50%',
              background: activeMode === 'director' ? 'linear-gradient(135deg, #0077b6, #00b4d8)' : 'linear-gradient(135deg, #059669, #34d399)',
              display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, color: '#fff'
            }}>
              {activeMode === 'director' ? '🎯' : '👥'}
            </div>
            <div>
              <div className="card-title" style={{ margin: 0 }}>
                {activeMode === 'director' ? 'Director Intelligence Assistant' : 'Staff Compliance Helpdesk'}
              </div>
              <div style={{ fontSize: 10, color: 'var(--success)' }}>
                ● Available 24/7 · Confidential · 2 CFR 200 Knowledge Base
              </div>
            </div>
          </div>

          <button className="btn btn-outline btn-sm" onClick={handleClearChat}>
            Clear Chat
          </button>
        </div>

        {/* STAFF IDENTITY STRIP (Optional - matching wireframe) */}
        {activeMode === 'staff' && (
          <div style={{
            background: 'var(--surface2)',
            borderRadius: 8,
            padding: '10px 14px',
            marginBottom: 12,
            display: 'flex',
            gap: 10,
            alignItems: 'center',
            flexWrap: 'wrap',
            border: '1px solid var(--border)'
          }}>
            <span style={{ fontSize: 11, color: 'var(--text-muted)', whiteSpace: 'nowrap', fontWeight: 600 }}>
              Your details (optional):
            </span>
            <input
              type="text"
              placeholder="Your name"
              value={staffName}
              onChange={(e) => setStaffName(e.target.value)}
              style={{ width: 140, padding: '5px 8px', fontSize: 11, border: '1px solid var(--border)', borderRadius: 4 }}
            />
            <select
              value={staffState}
              onChange={(e) => setStaffState(e.target.value)}
              style={{ width: 150, padding: '5px 8px', fontSize: 11, border: '1px solid var(--border)', borderRadius: 4 }}
            >
              <option value="">Select state...</option>
              <option>Lagos</option>
              <option>Kano</option>
              <option>Rivers</option>
              <option>Abuja FCT</option>
              <option>Kaduna</option>
              <option>Borno</option>
            </select>
            <select
              value={staffDonor}
              onChange={(e) => setStaffDonor(e.target.value)}
              style={{ width: 190, padding: '5px 8px', fontSize: 11, border: '1px solid var(--border)', borderRadius: 4 }}
            >
              <option value="">Select donor (if relevant)</option>
              <option>Department of State (DoS)</option>
              <option>USAID / PEPFAR</option>
              <option>FCDO / UK Aid</option>
              <option>EU / ECHO</option>
              <option>Global Fund</option>
              <option>UNICEF</option>
              <option>UNHCR</option>
              <option>World Bank</option>
              <option>Gates Foundation</option>
              <option>Other</option>
            </select>
          </div>
        )}

        {/* QUICK PROMPTS (Exact matching original wireframe) */}
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, marginBottom: 14, paddingBottom: 10, borderBottom: '1px solid var(--border)' }}>
          {activeMode === 'director' ? (
            <>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Summarise all open compliance issues across all states')}>
                📊 State Summary
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('What are the top 3 risks this quarter and recommended actions?')}>
                ⚠️ Top 3 Risks
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Draft a donor compliance update for Q1 2026')}>
                📝 Draft Donor Report
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Which states have the lowest CAP completion rate and what should I do?')}>
                ✅ CAP Status
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Summarise training gaps across states')}>
                🎓 Training Gaps
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('What lessons learned are most relevant for upcoming field activities?')}>
                💡 Lessons Learned
              </button>
            </>
          ) : (
            <>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Is it allowable to use donor funds to buy fuel for a generator at our field office?')}>
                ⛽ Fuel/generator costs
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Can we give a gift to a government official who helped with our programme?')}>
                🎁 Gifts to officials
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('What documentation do I need before making a cash transfer to a beneficiary?')}>
                💵 Cash transfers
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('A vendor is asking us to split a purchase into two invoices to stay below the threshold. Is this allowed?')}>
                🧾 Invoice splitting
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('Can programme funds be used to pay for a staff member\'s personal phone bill?')}>
                📱 Personal expenses
              </button>
              <button className="btn btn-outline btn-sm" style={{ fontSize: 11, padding: '4px 9px' }} onClick={() => handleSendMessage('What do I do if I suspect a colleague is committing fraud?')}>
                🚨 Report fraud
              </button>
            </>
          )}
        </div>

        {/* MESSAGES THREAD */}
        <div style={{
          minHeight: 280,
          maxHeight: 400,
          overflowY: 'auto',
          display: 'flex',
          flexDirection: 'column',
          gap: 12,
          padding: '8px 4px',
          marginBottom: 16
        }}>
          {activeMessages.map((msg) => (
            <div
              key={msg.id}
              style={{
                alignSelf: msg.sender === 'user' ? 'flex-end' : 'flex-start',
                maxWidth: '85%',
                background: msg.sender === 'user' ? 'var(--accent)' : 'var(--surface2)',
                color: msg.sender === 'user' ? '#ffffff' : 'var(--text)',
                border: msg.sender === 'user' ? 'none' : '1px solid var(--border)',
                borderRadius: 10,
                padding: '12px 16px',
                fontSize: 12,
                lineHeight: 1.65,
                boxShadow: 'var(--shadow-sm)'
              }}
            >
              <div style={{ whiteSpace: 'pre-wrap' }}>
                {msg.text}
              </div>
              <div style={{
                fontSize: 9,
                opacity: 0.75,
                marginTop: 4,
                textAlign: msg.sender === 'user' ? 'right' : 'left'
              }}>
                {msg.timestamp}
              </div>
            </div>
          ))}

          {isThinking && (
            <div style={{ alignSelf: 'flex-start', background: 'var(--surface2)', padding: '8px 12px', borderRadius: 8, fontSize: 11, color: 'var(--text-muted)' }}>
              <i className="fa-solid fa-spinner fa-spin" style={{ marginRight: 6 }}></i> Analyzing 2 CFR 200 & institutional policies...
            </div>
          )}
        </div>

        {/* INPUT BAR */}
        <form onSubmit={(e) => { e.preventDefault(); handleSendMessage(); }} style={{ display: 'flex', gap: 8 }}>
          <input
            type="text"
            placeholder={activeMode === 'director' ? "e.g. 'Which state needs most urgent attention this week?'" : "e.g. 'Is it allowable to pay a consultant without a written contract?'"}
            value={inputVal}
            onChange={(e) => setInputVal(e.target.value)}
            style={{
              flex: 1,
              padding: '10px 14px',
              fontSize: 12,
              border: '1px solid var(--border)',
              borderRadius: 6,
              background: 'var(--surface2)',
              outline: 'none'
            }}
          />
          <button
            type="submit"
            className="btn btn-primary"
            style={{
              padding: '10px 18px',
              fontWeight: 700,
              background: activeMode === 'staff' ? 'var(--success)' : 'var(--accent)',
              borderColor: activeMode === 'staff' ? 'var(--success)' : 'var(--accent)'
            }}
          >
            {activeMode === 'staff' ? 'Ask' : 'Send'}
          </button>
        </form>
      </div>

      {/* ESCALATION CARD (Exact matching original wireframe) */}
      {activeMode === 'staff' && (
        <div style={{
          background: '#fff8e1',
          border: '1px solid #ffe082',
          borderRadius: 10,
          padding: '14px 18px',
          marginTop: 16,
          display: 'flex',
          gap: 12,
          alignItems: 'center'
        }}>
          <div style={{ fontSize: 24 }}>🚨</div>
          <div style={{ flex: 1 }}>
            <div style={{ fontWeight: 700, color: '#92400e', fontSize: 12, marginBottom: 2 }}>
              Need to escalate a serious issue?
            </div>
            <div style={{ fontSize: 11, color: '#78350f', lineHeight: 1.4 }}>
              If your concern involves fraud, safeguarding, or serious misconduct, please report directly to the Compliance Unit — don't rely on this chat alone.
            </div>
          </div>
          <button
            className="btn btn-primary"
            style={{ background: '#d97706', borderColor: '#d97706', whiteSpace: 'nowrap', fontSize: 11 }}
            onClick={() => setActiveModule('complaints')}
          >
            Report Now
          </button>
        </div>
      )}
    </div>
  );
};
