@extends('layouts.app')
@section('title','My Dashboard')
@section('content')

{{-- Dashboard Header --}}
<div style="background:linear-gradient(135deg,#F5FAF5 0%,#fff 100%);border-bottom:1px solid var(--line);padding:40px 6%">
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#2D5A2E;margin-bottom:8px">Founder Dashboard</div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:700;color:#111;letter-spacing:-.03em">
                Welcome back, {{ session('user_name') }}! 👋
            </h1>
            <p style="color:#6B6B67;font-size:14px;margin-top:6px">Here's what's happening with your startups today</p>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('favorites.index') }}" class="btn btn-outline-dark">❤️ Favorites</a>
            <a href="{{ route('dashboard.analytics') }}" class="btn btn-outline-dark">📊 Analytics</a>
            <a href="{{ route('startups.create') }}" class="btn btn-green">+ New Listing</a>
        </div>
    </div>
</div>
</div>

<div class="container" style="padding:40px 6%">

    {{-- KPI Cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:36px">
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #2D5A2E">
            <div style="font-size:24px;margin-bottom:8px">👁️</div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Total Views</div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ number_format($totalViews) }}</div>
            <div style="font-size:12px;color:#2D5A2E;font-weight:600;margin-top:6px">↑ 12% this week</div>
        </div>
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #8A6914">
            <div style="font-size:24px;margin-bottom:8px">📩</div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Inquiries</div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ $totalInquiries }}</div>
            <div style="font-size:12px;color:#8A6914;font-weight:600;margin-top:6px">From investors</div>
        </div>
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #1E3A5F">
            <div style="font-size:24px;margin-bottom:8px">🚀</div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Active Listings</div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">{{ $activeCount }}</div>
            <div style="font-size:12px;color:#1E3A5F;font-weight:600;margin-top:6px">Live on marketplace</div>
        </div>
        <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;border-left:4px solid #7A3B2E">
            <div style="font-size:24px;margin-bottom:8px">💰</div>
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9A9A92;margin-bottom:6px">Avg Asking</div>
            <div style="font-family:'Cormorant Garamond',serif;font-size:36px;font-weight:700;color:#111;letter-spacing:-.02em">
                {{ $avgValuation > 0 ? '$'.number_format($avgValuation/1000).'K' : '—' }}
            </div>
            <div style="font-size:12px;color:#7A3B2E;font-weight:600;margin-top:6px">Across listings</div>
        </div>
    </div>

    {{-- My Startups Table --}}
    <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700">My Startups</h2>
            <a href="{{ route('startups.create') }}" class="btn btn-green btn-sm">+ Add New</a>
        </div>

        @if($startups->isEmpty())
        <div style="text-align:center;padding:60px 20px;background:#F5FAF5;border-radius:12px">
            <div style="font-size:48px;margin-bottom:16px">🚀</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700;margin-bottom:8px">No startups yet</h3>
            <p style="color:#6B6B67;margin-bottom:20px">List your first startup and reach 890+ investors!</p>
            <a href="{{ route('startups.create') }}" class="btn btn-green">List Your Startup →</a>
        </div>
        @else
        <table class="dtbl">
            <thead>
                <tr>
                    <th>Startup</th>
                    <th>Stage</th>
                    <th>ARR</th>
                    <th>Asking</th>
                    <th>Inquiries</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($startups as $startup)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#2D5A2E,#3A7A3C);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0">
                                {{ strtoupper(substr($startup->name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13px">{{ $startup->name }}</div>
                                <div style="font-size:11px;color:#9A9A92">{{ \Str::limit($startup->tagline, 35) }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-size:12px">{{ $startup->stage }}</span></td>
                    <td><strong>{{ $startup->formatted_arr }}</strong></td>
                    <td>{{ $startup->formatted_asking }}</td>
                    <td>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700">{{ $startup->inq_count }}</span>
                    </td>
                    <td>
                        <span style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:700">{{ number_format($startup->views) }}</span>
                    </td>
                    <td>
                        @if($startup->status === 'active')
                            <span class="dpill dp-l">✅ Active</span>
                        @elseif($startup->status === 'pending')
                            <span class="dpill dp-r">⏳ Pending</span>
                        @else
                            <span class="dpill dp-c">{{ ucfirst($startup->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('startups.show', $startup) }}" class="btn btn-outline-dark btn-xs">👁️</a>
                            <a href="{{ route('startups.edit', $startup) }}" class="btn btn-outline-dark btn-xs">✏️</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Recent Inquiries --}}
    @if(count($inquiries) > 0)
    <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px;margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px">
            <h2 style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:700">Recent Inquiries 📩</h2>
            <a href="{{ route('dashboard.inquiries') }}" style="font-size:12px;color:#2D5A2E;font-weight:600;text-decoration:none">View All →</a>
        </div>
        <table class="dtbl">
            <thead>
                <tr><th>Investor</th><th>Startup</th><th>Type</th><th>Message</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($inquiries as $inq)
                <tr>
                    <td>
                        <strong>{{ $inq->investor_name }}</strong><br>
                        <span style="font-size:11px;color:#9A9A92">{{ $inq->organisation }}</span>
                    </td>
                    <td style="font-size:13px">{{ $inq->startup_name ?? 'N/A' }}</td>
                    <td>
                        <span style="font-size:12px;background:#EBF4EB;color:#2D5A2E;padding:3px 8px;border-radius:100px;font-weight:600">
                            {{ ucfirst($inq->interest_type) }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:#6B6B67;max-width:200px">{{ \Str::limit($inq->message, 50) }}</td>
                    <td style="font-size:11px;color:#9A9A92">{{ \Carbon\Carbon::parse($inq->created_at)->diffForHumans() }}</td>
                    <td>
                        @if($inq->status === 'unread')
                            <span class="dpill dp-r">Unread</span>
                        @elseif($inq->status === 'replied')
                            <span class="dpill dp-l">Replied</span>
                        @else
                            <span class="dpill dp-c">Closed</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Quick Links --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
        <a href="{{ route('dashboard.inquiries') }}" style="background:#fff;border:1px solid var(--line);border-radius:14px;padding:20px;text-align:center;text-decoration:none;transition:all .2s" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
            <div style="font-size:28px;margin-bottom:8px">📋</div>
            <div style="font-size:13px;font-weight:700;color:#111">All Inquiries</div>
        </a>
        <a href="{{ route('favorites.index') }}" style="background:#fff;border:1px solid var(--line);border-radius:14px;padding:20px;text-align:center;text-decoration:none;transition:all .2s" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
            <div style="font-size:28px;margin-bottom:8px">❤️</div>
            <div style="font-size:13px;font-weight:700;color:#111">Saved Startups</div>
        </a>

        @if(session('user_role') === 'admin')
        <a href="{{ route('admin.index') }}" style="background:linear-gradient(135deg,#111,#1a2a1a);border:1px solid #333;border-radius:14px;padding:20px;text-align:center;text-decoration:none">
            <div style="font-size:28px;margin-bottom:8px">⚙️</div>
            <div style="font-size:13px;font-weight:700;color:#fff">Admin Panel</div>
        </a>
        @else
        <a href="{{ route('startups.create') }}" style="background:linear-gradient(135deg,#2D5A2E,#3A7A3C);border-radius:14px;padding:20px;text-align:center;text-decoration:none">
            <div style="font-size:28px;margin-bottom:8px">➕</div>
            <div style="font-size:13px;font-weight:700;color:#fff">New Listing</div>
        </a>
        @endif
    </div>
</div>
@endsection
