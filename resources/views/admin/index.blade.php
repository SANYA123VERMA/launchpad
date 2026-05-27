@extends('layouts.app')

@section('title', 'Admin Panel Dashboard — LaunchPad Market')

@section('content')

{{-- Admin Header --}}
<div style="background:linear-gradient(135deg,#111110 0%,#1E3A1E 100%);padding:40px 6%;border-bottom:1px solid #2D5A2E">
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#BDD9BE;margin-bottom:8px">⚙️ Admin Control Panel</div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:#fff;letter-spacing:-.03em">LaunchPad Admin</h1>
            <p style="color:#A8A8A3;font-size:14px;margin-top:6px">Manage startups, user KYC accreditations, investments, and inquiries</p>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.startups', ['status'=>'pending']) }}"
               style="background:#C0392B;color:#fff;padding:10px 20px;border-radius:100px;font-size:13px;font-weight:700;text-decoration:none">
                ⏳ Pending Startups ({{ $pendingCount }})
            </a>
            <a href="{{ route('home') }}" style="border:1px solid #333;color:#999;padding:10px 20px;border-radius:100px;font-size:13px;font-weight:600;text-decoration:none">← View Site</a>
        </div>
    </div>
</div>
</div>

<div class="container" style="padding:40px 6%">

    {{-- KPI Row --}}
    <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:36px">
        @foreach([
            ['Total Users', $totalUsers, '#2D5A2E', '👥'],
            ['Active Startups', $activeCount, '#8A6914', '🚀'],
            ['Pending Startups', $pendingCount, '#C0392B', '⏳'],
            ['Inquiries', $totalInquiries, '#1E3A5F', '📩'],
            ['Pledged volume', '$' . number_format($totalInvestmentVolume), '#2D5A2E', '💰'],
            ['Pending Pledges', $pendingInvestmentCount, '#C0392B', '📈'],
        ] as $kpi)
        <div style="background:#fff;border:1px solid var(--line);border-radius:14px;padding:18px;border-top:3px solid {{ $kpi[2] }};box-shadow:var(--sh1)">
            <div style="font-size:20px;margin-bottom:8px">{{ $kpi[3] }}</div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:4px">{{ $kpi[0] }}</div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;color:#111">{{ $kpi[1] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div style="display:flex;gap:12px;margin-bottom:36px;flex-wrap:wrap">
        <a href="{{ route('admin.startups') }}" class="btn btn-outline-dark btn-sm">🚀 Startups Manager</a>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-dark btn-sm">👥 Users & KYC</a>
        <a href="{{ route('admin.investments') }}" class="btn btn-green btn-sm">📈 Pledged Investments</a>
        <a href="{{ route('admin.inquiries') }}" class="btn btn-dark btn-sm">📩 Inquiries Dashboard</a>
        <a href="{{ route('admin.categories') }}" class="btn btn-outline-dark btn-sm">🏷️ Categories</a>
    </div>

    <!-- Analytics Charts Row -->
    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px;margin-bottom:36px">
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:26px;box-shadow:var(--sh1)">
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px">Investment Volume by Startup (Top 5 Approved)</h3>
            <div style="position:relative;height:280px;width:100%">
                @if(count($chartStartupsLabels) > 0)
                    <canvas id="investmentVolumeChart"></canvas>
                @else
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--ink3);text-align:center;padding:20px;background:var(--off);border-radius:12px;border:1px dashed var(--line2)">
                        <div style="font-size:32px;margin-bottom:8px">💰</div>
                        <div style="font-size:14px;font-weight:600;color:var(--ink)">No Approved Investments Yet</div>
                        <p style="font-size:12px;color:var(--ink3);margin-top:4px;max-width:280px">Approved pledges with successful payments will show up here.</p>
                    </div>
                @endif
            </div>
        </div>
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:26px;box-shadow:var(--sh1)">
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px">Startup Categories Breakdown</h3>
            <div style="position:relative;height:280px;width:100%">
                @if(count($chartCategoryLabels) > 0)
                    <canvas id="categoryBreakdownChart"></canvas>
                @else
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--ink3);text-align:center;padding:20px;background:var(--off);border-radius:12px;border:1px dashed var(--line2)">
                        <div style="font-size:32px;margin-bottom:8px">🏷️</div>
                        <div style="font-size:14px;font-weight:600;color:var(--ink)">No Startups Registered Yet</div>
                        <p style="font-size:12px;color:var(--ink3);margin-top:4px;max-width:280px">Registered listings categorized by industry will populate this breakdown.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

        {{-- Recent Startups --}}
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:26px;box-shadow:var(--sh1)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700">Recent Startups</h2>
                <a href="{{ route('admin.startups') }}" style="font-size:12px;color:#2D5A2E;font-weight:600;text-decoration:none">View All →</a>
            </div>
            @foreach($recentStartups as $startup)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)">
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#2D5A2E,#3A7A3C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px">
                        {{ strtoupper(substr($startup->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700">{{ $startup->name }}</div>
                        <div style="font-size:11px;color:#9A9A92">{{ $startup->founder->name ?? 'N/A' }} · {{ $startup->stage }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    @if($startup->status === 'active')
                        <span class="dpill dp-l">Active</span>
                    @elseif($startup->status === 'pending')
                        <span class="dpill dp-r">Pending</span>
                        <form action="{{ route('admin.startups.approve', $startup) }}" method="POST">
                            @csrf
                            <button class="btn btn-green btn-xs">✅</button>
                        </form>
                    @else
                        <span class="dpill dp-c">{{ ucfirst($startup->status) }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Recent Users --}}
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:26px;box-shadow:var(--sh1)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700">Recent Users</h2>
                <a href="{{ route('admin.users') }}" style="font-size:12px;color:#2D5A2E;font-weight:600;text-decoration:none">View All →</a>
            </div>
            @foreach($recentUsers as $user)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)">
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1E3A5F,#2B5490);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700">{{ $user->name }}</div>
                        <div style="font-size:11px;color:#9A9A92">{{ $user->email }}</div>
                    </div>
                </div>
                <span class="dpill dp-l" style="font-size:10px">{{ ucfirst($user->role) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(count($chartStartupsLabels) > 0)
    // 1. Investment volume chart (Bar Chart)
    const investmentCtx = document.getElementById('investmentVolumeChart').getContext('2d');
    new Chart(investmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartStartupsLabels) !!},
            datasets: [{
                label: 'Capital Pledged ($)',
                data: {!! json_encode($chartStartupsData) !!},
                backgroundColor: 'rgba(45, 90, 46, 0.75)',
                borderColor: 'rgba(45, 90, 46, 1)',
                borderWidth: 1.5,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '$' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
    @endif

    @if(count($chartCategoryLabels) > 0)
    // 2. Categories breakdown chart (Doughnut Chart)
    const categoryCtx = document.getElementById('categoryBreakdownChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartCategoryLabels) !!},
            datasets: [{
                data: {!! json_encode($chartCategoryData) !!},
                backgroundColor: [
                    '#2D5A2E',
                    '#8A6914',
                    '#1E3A5F',
                    '#7A3B2E',
                    '#A8A8A3',
                    '#B8891A'
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            }
        }
    });
    @endif
});
</script>
@endsection
