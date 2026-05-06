@extends('admin.layout')
@section('title', 'Guests')

@section('content')

<div class="filter-bar" style="align-items: center;">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search guests..." id="searchInput">
    </div>
    <div style="display: flex; gap: 12px; margin-left: auto; align-items: center;">
        <a href="{{ route('admin.guests.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> Add Guest
        </a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Guests</div>
        <span style="font-size:12px;color:#AAA;">{{ $guests->total() ?? 0 }} guests total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Reservations</th>
                <th>Member Since</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $guest)
            <tr>
                <td><strong>{{ $guest->first_name }} {{ $guest->last_name }}</strong></td>
                <td>{{ $guest->email ?? '—' }}</td>
                <td>{{ $guest->phone ?? '—' }}</td>
                <td><span style="background:#FDF8EF;padding:4px 8px;border-radius:4px;font-size:12px;">{{ $guest->reservations_count }}</span></td>
                <td>{{ $guest->created_at->format('M d, Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.guests.show', $guest->guest_id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.guests.edit', $guest->guest_id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.guests.destroy', $guest->guest_id) }}" onsubmit="return confirm('Delete this guest?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6"><div class="empty-state"><i class="fas fa-users"></i><p>No guests found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $guests->links() }}</div>
</div>

<style>


table { width: 100%; border-collapse: collapse; }
thead { background: #F9F6F0; }
th { padding: 14px 16px; text-align: left; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #999; font-weight: 600; border-bottom: 1px solid #EAE6DF; }
td { padding: 14px 16px; border-bottom: 1px solid #F5F2ED; font-size: 13px; }
tbody tr:hover { background: #FDFAF5; }
tbody tr:last-child td { border-bottom: 1px solid #EAE6DF; }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.2s; }
.btn-gold { background: #C9A84C; color: #FFF; }
.btn-gold:hover { background: #B89A3F; }
.btn-outline { background: #FFF; color: #2C2C2C; border: 1px solid #EAE6DF; }
.btn-outline:hover { background: #FAFAF8; }
.btn-danger { background: #F5E6E6; color: #C62828; }
.btn-danger:hover { background: #E8CCCC; }
.btn-sm { padding: 8px 12px; font-size: 12px; }

.empty-state { text-align: center; padding: 48px 24px; color: #999; }
.empty-state i { font-size: 48px; display: block; margin-bottom: 12px; opacity: 0.3; }
.empty-state p { font-size: 14px; }

.pagination-wrap { padding: 16px 0; text-align: center; }
</style>

@endsection
