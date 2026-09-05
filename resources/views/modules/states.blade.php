@extends('layouts.app')

@section('content')
<div style="padding-bottom: 40px; width: 100%; max-width: 100%; box-sizing: border-box; overflow-x: hidden;" id="statesModuleContainer">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin: 0;">
                    State Regional Offices & Operational Clusters
                </h2>
                <span class="pill pill-closed" style="font-size: 10px;">6 Regional Hubs</span>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">
                Cluster coordination, field office compliance standing, grievance density, and scheduled audits
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="alert('Scheduling multi-state audit cycle...')">
                <i class="fa-solid fa-calendar-check"></i> Schedule Cluster Audit
            </button>
        </div>
    </div>

    <!-- 6 Cluster Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- Lagos -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--text);">Lagos State</div>
                <span class="pill pill-closed">Cluster A</span>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Lead: <strong>Dr. Ngozi Adeyemi</strong> · 95 Staff</div>
            <div style="margin: 14px 0 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span>Compliance Score</span>
                    <strong style="color: var(--success);">78%</strong>
                </div>
                <div style="height: 6px; background: var(--surface2); border-radius: 3px; overflow: hidden;">
                    <div style="width: 78%; height: 100%; background: var(--success);"></div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 11px; margin-top: 10px;">
                <span class="pill pill-progress">2 Complaints</span>
                <span class="pill pill-closed">1 Active CAP</span>
            </div>
        </div>

        <!-- Kano -->
        <div class="card" style="margin-bottom: 0; border: 1px solid #fecaca; background: #fff5f5;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: #991b1b;">Kano State</div>
                <span class="pill pill-open">Cluster B (Priority)</span>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Lead: <strong>Musa Ibrahim</strong> · 82 Staff</div>
            <div style="margin: 14px 0 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span>Compliance Score</span>
                    <strong style="color: var(--danger);">62% (Audit Alert)</strong>
                </div>
                <div style="height: 6px; background: #fee2e2; border-radius: 3px; overflow: hidden;">
                    <div style="width: 62%; height: 100%; background: var(--danger);"></div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 11px; margin-top: 10px;">
                <span class="pill pill-open">3 Complaints</span>
                <span class="pill pill-open">2 Active CAPs</span>
            </div>
        </div>

        <!-- Rivers -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--text);">Rivers State</div>
                <span class="pill pill-closed">Cluster C</span>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Lead: <strong>Chidi Okafor</strong> · 74 Staff</div>
            <div style="margin: 14px 0 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span>Compliance Score</span>
                    <strong style="color: var(--accent);">81%</strong>
                </div>
                <div style="height: 6px; background: var(--surface2); border-radius: 3px; overflow: hidden;">
                    <div style="width: 81%; height: 100%; background: var(--accent);"></div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 11px; margin-top: 10px;">
                <span class="pill pill-closed">1 Complaint</span>
                <span class="pill pill-closed">1 Active CAP</span>
            </div>
        </div>

        <!-- Abuja -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--text);">Abuja HQ & FCT</div>
                <span class="pill pill-closed">HQ Command</span>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Lead: <strong>Amaka Okonkwo</strong> · 110 Staff</div>
            <div style="margin: 14px 0 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span>Compliance Score</span>
                    <strong style="color: var(--success);">88%</strong>
                </div>
                <div style="height: 6px; background: var(--surface2); border-radius: 3px; overflow: hidden;">
                    <div style="width: 88%; height: 100%; background: var(--success);"></div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 11px; margin-top: 10px;">
                <span class="pill pill-closed">1 Complaint</span>
                <span class="pill pill-closed">0 Active CAPs</span>
            </div>
        </div>

        <!-- Kaduna -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--text);">Kaduna State</div>
                <span class="pill pill-closed">Cluster B</span>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Lead: <strong>Hassan Suleiman</strong> · 68 Staff</div>
            <div style="margin: 14px 0 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span>Compliance Score</span>
                    <strong style="color: var(--warning);">71%</strong>
                </div>
                <div style="height: 6px; background: var(--surface2); border-radius: 3px; overflow: hidden;">
                    <div style="width: 71%; height: 100%; background: var(--warning);"></div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 11px; margin-top: 10px;">
                <span class="pill pill-progress">2 Complaints</span>
                <span class="pill pill-progress">2 Active CAPs</span>
            </div>
        </div>

        <!-- Borno -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 800; color: var(--text);">Borno State</div>
                <span class="pill pill-closed">Northeast Hub</span>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">Lead: <strong>Fatima Bakura</strong> · 61 Staff</div>
            <div style="margin: 14px 0 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span>Compliance Score</span>
                    <strong style="color: var(--warning);">69%</strong>
                </div>
                <div style="height: 6px; background: var(--surface2); border-radius: 3px; overflow: hidden;">
                    <div style="width: 69%; height: 100%; background: var(--warning);"></div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; font-size: 11px; margin-top: 10px;">
                <span class="pill pill-open">1 Complaint</span>
                <span class="pill pill-closed">1 Active CAP</span>
            </div>
        </div>
    </div>
</div>
@endsection
