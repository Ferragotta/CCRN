@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="aiModuleContainer">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0;">
                    ComplianceIQ AI Intelligence Assistant
                </h2>
                <span class="pill pill-closed" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd; font-size: 10px;">GPT-4o Regulatory Engine</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">
                Query compliance policies, draft CAPs, analyze risk anomalies, and generate donor briefing notes
            </p>
        </div>
    </div>

    <!-- Chat UI -->
    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 16px; height: 600px;">
        <!-- Left: History & Prompts -->
        <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between; padding: 14px;">
            <div>
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 10px;">Audit Queries</div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <div style="padding: 8px 10px; background: rgba(2, 54, 123, 0.06); color: var(--accent); border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                        💬 Q1 Risk Analysis
                    </div>
                    <div style="padding: 8px 10px; color: var(--text-dim); border-radius: 6px; font-size: 11px; cursor: pointer;">
                        💬 PSEA Policy Clauses
                    </div>
                    <div style="padding: 8px 10px; color: var(--text-dim); border-radius: 6px; font-size: 11px; cursor: pointer;">
                        💬 Kano Cluster Briefing
                    </div>
                </div>
            </div>
            <div style="font-size: 10px; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 10px;">
                CCCRN Internal Encrypted Model
            </div>
        </div>

        <!-- Right: Active Chat Window -->
        <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
            <!-- Message List -->
            <div id="aiChatWindow" style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 16px;">
                <!-- User Msg -->
                <div style="align-self: flex-end; max-width: 70%; background: var(--accent); color: #ffffff; padding: 12px 16px; border-radius: 12px 12px 2px 12px; font-size: 12px; line-height: 1.5;">
                    What are the top 3 critical compliance risks facing the organization this quarter?
                </div>

                <!-- AI Response -->
                <div style="align-self: flex-start; max-width: 80%; background: var(--surface2); color: var(--text); padding: 14px 18px; border-radius: 12px 12px 12px 2px; font-size: 12px; line-height: 1.6; border: 1px solid var(--border);">
                    <div style="font-weight: 700; color: var(--accent); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-robot"></i> ComplianceIQ AI Engine
                    </div>
                    Based on real-time data across all 6 state offices:
                    <ol style="margin: 6px 0; padding-left: 18px;">
                        <li><strong>Kano Vendor Invoicing (RSK-024):</strong> Risk score 20 (Critical). Active investigation INV-012 underway.</li>
                        <li><strong>PSEA Incident Escalation (RSK-023):</strong> Risk score 12 (High). CAP-012 addresses reporting bottlenecks.</li>
                        <li><strong>Travel Advance Reconciliations (POL-TRV-03):</strong> 14 travel boarding passes pending verification.</li>
                    </ol>
                    <em>Recommended Action: Prioritize Kano cluster audit and verify all escrow gates.</em>
                </div>
            </div>

            <!-- Input Box -->
            <div style="padding: 14px; border-top: 1px solid var(--border); background: var(--surface); display: flex; gap: 10px;">
                <input type="text" id="aiInput" placeholder="Ask ComplianceIQ about policies, risks, or draft a CAP..." style="flex: 1; height: 40px; padding: 0 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 12px; outline: none;">
                <button class="btn btn-primary" onclick="sendAiMessage()" style="height: 40px; padding: 0 20px;">
                    <i class="fa-solid fa-paper-plane"></i> Send
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function sendAiMessage() {
        const input = document.getElementById('aiInput');
        const text = input.value.trim();
        if (!text) return;

        const win = document.getElementById('aiChatWindow');

        // User bubble
        const userDiv = document.createElement('div');
        userDiv.style.cssText = 'align-self: flex-end; max-width: 70%; background: var(--accent); color: #ffffff; padding: 12px 16px; border-radius: 12px 12px 2px 12px; font-size: 12px; line-height: 1.5;';
        userDiv.innerText = text;
        win.appendChild(userDiv);
        input.value = '';

        // AI bubble
        setTimeout(() => {
            const aiDiv = document.createElement('div');
            aiDiv.style.cssText = 'align-self: flex-start; max-width: 80%; background: var(--surface2); color: var(--text); padding: 14px 18px; border-radius: 12px 12px 12px 2px; font-size: 12px; line-height: 1.6; border: 1px solid var(--border);';
            aiDiv.innerHTML = '<div style="font-weight:700; color:var(--accent); margin-bottom:6px;"><i class="fa-solid fa-robot"></i> ComplianceIQ AI Engine</div>Acknowledged query regarding <em>"' + text + '"</em>. Querying compliance database and verified policy documents... No irregularities detected.';
            win.appendChild(aiDiv);
            win.scrollTop = win.scrollHeight;
        }, 600);

        win.scrollTop = win.scrollHeight;
    }
</script>
@endsection
