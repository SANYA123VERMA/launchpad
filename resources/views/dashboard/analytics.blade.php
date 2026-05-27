@extends('layouts.app')
@section('title', 'Analytics Dashboard')
@section('content')

{{-- Header Banner --}}
@if(session('user_role') === 'founder')
<div style="background:linear-gradient(135deg,#F5FAF5 0%,#fff 100%);border-bottom:1px solid var(--line);padding:40px 6%">
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#2D5A2E;margin-bottom:8px">Founder Analytics</div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:#111;letter-spacing:-.03em">
                Performance Analytics 📊
            </h1>
            <p style="color:#6B6B67;font-size:14px;margin-top:6px">Deep dive into views, inquiries, and listing activity</p>
        </div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-dark">← Back to Dashboard</a>
    </div>
</div>
</div>

<div class="container" style="padding:40px 6%">
    @if($startups->isEmpty())
        <div style="text-align:center;padding:80px 20px;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--sh1)">
            <div style="font-size:54px;margin-bottom:20px">📊</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;margin-bottom:10px">No listing data to analyze</h3>
            <p style="color:#6B6B67;margin-bottom:24px;font-size:15px">List your startup first to track performance views and inquiries.</p>
            <a href="{{ route('startups.create') }}" class="btn btn-green">List Your Startup now →</a>
        </div>
    @else
        {{-- KPI Summary Cards --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:36px">
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #2D5A2E">
                <div style="font-size:24px;margin-bottom:8px">🚀</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Total Startups</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ $startups->count() }}</div>
                <div style="font-size:12px;color:#2D5A2E;font-weight:600;margin-top:6px">Registered listings</div>
            </div>
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #1E3A5F">
                <div style="font-size:24px;margin-bottom:8px">👁️</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Total Views</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ number_format($startups->sum('views')) }}</div>
                <div style="font-size:12px;color:#1E3A5F;font-weight:600;margin-top:6px">Cumulative reach</div>
            </div>
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #8A6914">
                <div style="font-size:24px;margin-bottom:8px">📩</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Total Inquiries</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ $startups->sum('inquiries_count') }}</div>
                <div style="font-size:12px;color:#8A6914;font-weight:600;margin-top:6px">Investor connections</div>
            </div>
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #7A3B2E">
                <div style="font-size:24px;margin-bottom:8px">📈</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Avg. Conversion</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">
                    {{ $startups->sum('views') > 0 ? number_format(($startups->sum('inquiries_count') / $startups->sum('views')) * 100, 1) . '%' : '0%' }}
                </div>
                <div style="font-size:12px;color:#7A3B2E;font-weight:600;margin-top:6px">Inquiry-to-view ratio</div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px">
            {{-- Views & Inquiries by Startup --}}
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;box-shadow:var(--sh1)">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px;color:#111">Startup Performance Breakdown</h3>
                <div style="position:relative;height:350px">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            {{-- Inquiry Share --}}
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;box-shadow:var(--sh1);display:flex;flex-direction:column">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px;color:#111">Inquiry Distribution</h3>
                <div style="position:relative;height:250px;margin:auto;width:100%">
                    @if($startups->sum('inquiries_count') > 0)
                        <canvas id="inquiryShareChart"></canvas>
                    @else
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--ink3);text-align:center;padding:20px;background:var(--off);border-radius:12px;border:1px dashed var(--line2)">
                            <div style="font-size:32px;margin-bottom:8px">📩</div>
                            <div style="font-size:14px;font-weight:600;color:var(--ink)">No Inquiries Received Yet</div>
                            <p style="font-size:11px;color:var(--ink3);margin-top:4px;max-width:220px">When investors reach out, their interest share will display here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Financial Scatter / Comparative Charts --}}
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;box-shadow:var(--sh1);margin-bottom:24px">
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px;color:#111">Financial Metrics Comparison (ARR vs. Asking Price)</h3>
            <div style="position:relative;height:350px">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    @endif
</div>
@else
{{-- Investor / Acquirer / Advisor --}}
<div style="background:linear-gradient(135deg,#FAF6F0 0%,#fff 100%);border-bottom:1px solid var(--line);padding:40px 6%">
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#8A6914;margin-bottom:8px">
                {{ ucfirst(session('user_role')) }} Analytics
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:#111;letter-spacing:-.03em">
                Portfolio & Interest Analytics 📊
            </h1>
            <p style="color:#6B6B67;font-size:14px;margin-top:6px">Analyzing your favorited sectors and interaction types</p>
        </div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-dark">← Back to Dashboard</a>
    </div>
</div>
</div>

<div class="container" style="padding:40px 6%">
    @if($favoritesByCategory->isEmpty() && $inquiriesByType->isEmpty())
        <div style="text-align:center;padding:80px 20px;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:var(--sh1)">
            <div style="font-size:54px;margin-bottom:20px">📈</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:700;margin-bottom:10px">No activity data to analyze</h3>
            <p style="color:#6B6B67;margin-bottom:24px;font-size:15px">Save startups to favorites or submit inquiries to populate this dashboard.</p>
            <a href="{{ route('home') }}" class="btn btn-dark">Browse Startups now →</a>
        </div>
    @else
        {{-- KPI Cards --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:36px">
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #8A6914">
                <div style="font-size:24px;margin-bottom:8px">❤️</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Favorited Sectors</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ $favoritesByCategory->count() }}</div>
                <div style="font-size:12px;color:#8A6914;font-weight:600;margin-top:6px">Distinct categories</div>
            </div>
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #2D5A2E">
                <div style="font-size:24px;margin-bottom:8px">📩</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Inquiries Submitted</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ $inquiriesByType->sum('count') }}</div>
                <div style="font-size:12px;color:#2D5A2E;font-weight:600;margin-top:6px">Active outreach</div>
            </div>
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #1E3A5F">
                <div style="font-size:24px;margin-bottom:8px">⭐</div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Top Category Interest</div>
                <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $favoritesByCategory->first() ? $favoritesByCategory->sortByDesc('count')->first()->name : 'None' }}
                </div>
                <div style="font-size:12px;color:#1E3A5F;font-weight:600;margin-top:6px">Most saved industry</div>
            </div>
        </div>

        {{-- Investor Charts --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
            {{-- Favorites by Category --}}
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;box-shadow:var(--sh1);display:flex;flex-direction:column">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px;color:#111">Interests by Category</h3>
                <div style="position:relative;height:300px;margin:auto;width:100%">
                    @if(!$favoritesByCategory->isEmpty())
                        <canvas id="categoryChart"></canvas>
                    @else
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--ink3);text-align:center;padding:20px;background:var(--off);border-radius:12px;border:1px dashed var(--line2)">
                            <div style="font-size:32px;margin-bottom:8px">❤️</div>
                            <div style="font-size:14px;font-weight:600;color:var(--ink)">No Favorites Added Yet</div>
                            <p style="font-size:11px;color:var(--ink3);margin-top:4px;max-width:220px">Favorite startups to see your interest breakdown by category.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Inquiry Type breakdown --}}
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;box-shadow:var(--sh1);display:flex;flex-direction:column">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:700;margin-bottom:20px;color:#111">Outreach Types</h3>
                <div style="position:relative;height:300px;margin:auto;width:100%">
                    @if(!$inquiriesByType->isEmpty())
                        <canvas id="inquiryTypeChart"></canvas>
                    @else
                        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--ink3);text-align:center;padding:20px;background:var(--off);border-radius:12px;border:1px dashed var(--line2)">
                            <div style="font-size:32px;margin-bottom:8px">📩</div>
                            <div style="font-size:14px;font-weight:600;color:var(--ink)">No Inquiries Sent Yet</div>
                            <p style="font-size:11px;color:var(--ink3);margin-top:4px;max-width:220px">Inquiries sent to founders will show up here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endif

{{-- Load Chart.js and scripts --}}
@php
    $hasData = false;
    if (session('user_role') === 'founder') {
        if (isset($startups) && !$startups->isEmpty()) {
            $hasData = true;
        }
    } else {
        if ((isset($favoritesByCategory) && !$favoritesByCategory->isEmpty()) || (isset($inquiriesByType) && !$inquiriesByType->isEmpty())) {
            $hasData = true;
        }
    }
@endphp

@if($hasData)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const isFounder = {{ session('user_role') === 'founder' ? 'true' : 'false' }};

    if (isFounder) {
        // Founder Performance Chart (Views vs. Inquiries)
        const startupNames = {!! json_encode($startups->pluck('name')) !!};
        const startupViews = {!! json_encode($startups->pluck('views')) !!};
        const startupInquiries = {!! json_encode($startups->pluck('inquiries_count')) !!};

        new Chart(document.getElementById('performanceChart'), {
            type: 'bar',
            data: {
                labels: startupNames,
                datasets: [
                    {
                        label: 'Views',
                        data: startupViews,
                        backgroundColor: 'rgba(45, 90, 46, 0.75)', // Sage Green
                        borderColor: '#2D5A2E',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Inquiries',
                        data: startupInquiries,
                        backgroundColor: 'rgba(138, 105, 20, 0.75)', // Gold
                        borderColor: '#8A6914',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Inter', size: 12, weight: 500 }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Views', font: { family: 'Inter', weight: 600 } },
                        grid: { color: '#E8E4DC' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Inquiries', font: { family: 'Inter', weight: 600 } },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });

        @if($startups->sum('inquiries_count') > 0)
        // Founder Inquiry Share Doughnut
        new Chart(document.getElementById('inquiryShareChart'), {
            type: 'doughnut',
            data: {
                labels: startupNames,
                datasets: [{
                    data: startupInquiries,
                    backgroundColor: [
                        '#2D5A2E', // Sage Green
                        '#8A6914', // Gold
                        '#1E3A5F', // Deep Blue
                        '#7A3B2E', // Rust
                        '#4A4A45', // Charcoal
                        '#3A7A3C',
                        '#B8891A',
                        '#C5D8F0'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { family: 'Inter', size: 11 }
                        }
                    }
                }
            }
        });
        @endif

        // Founder Financials Compare Chart (Horizontal Bar Chart)
        const startupArr = {!! json_encode($startups->pluck('arr')) !!};
        const startupAsking = {!! json_encode($startups->pluck('asking_price')) !!};

        new Chart(document.getElementById('financialChart'), {
            type: 'bar',
            data: {
                labels: startupNames,
                datasets: [
                    {
                        label: 'ARR ($)',
                        data: startupArr,
                        backgroundColor: 'rgba(30, 58, 95, 0.75)', // Deep Blue
                        borderColor: '#1E3A5F',
                        borderWidth: 1.5,
                        borderRadius: 6
                    },
                    {
                        label: 'Asking Price ($)',
                        data: startupAsking,
                        backgroundColor: 'rgba(122, 59, 46, 0.75)', // Rust
                        borderColor: '#7A3B2E',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Inter', size: 12, weight: 500 } }
                    }
                },
                scales: {
                    y: {
                        grid: { color: '#E8E4DC' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return '$' + (value / 1000000) + 'M';
                                if (value >= 1000) return '$' + (value / 1000) + 'K';
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

    } else {
        @if(!$favoritesByCategory->isEmpty())
        // Investor Interests by Category Chart
        const categories = {!! json_encode($favoritesByCategory->pluck('name')) !!};
        const categoryCounts = {!! json_encode($favoritesByCategory->pluck('count')) !!};

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: categoryCounts,
                    backgroundColor: [
                        '#8A6914', // Gold
                        '#2D5A2E', // Sage Green
                        '#1E3A5F', // Deep Blue
                        '#7A3B2E', // Rust
                        '#4A4A45', // Charcoal
                        '#B8891A',
                        '#3A7A3C'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { family: 'Inter', size: 11 } }
                    }
                }
            }
        });
        @endif

        @if(!$inquiriesByType->isEmpty())
        // Investor Inquiries by Type Chart
        const inquiryTypes = {!! json_encode($inquiriesByType->pluck('interest_type')) !!}.map(t => {
            if (!t) return 'Unknown';
            return t.charAt(0).toUpperCase() + t.slice(1);
        });
        const inquiryCounts = {!! json_encode($inquiriesByType->pluck('count')) !!};

        new Chart(document.getElementById('inquiryTypeChart'), {
            type: 'bar',
            data: {
                labels: inquiryTypes,
                datasets: [{
                    label: 'Inquiries Sent',
                    data: inquiryCounts,
                    backgroundColor: 'rgba(138, 105, 20, 0.75)', // Gold
                    borderColor: '#8A6914',
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        grid: { color: '#E8E4DC' },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
        @endif
    }
});
</script>
@endif
@endsection
