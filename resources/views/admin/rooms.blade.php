@extends('admin.layout')
@section('title', 'Rooms')

@section('content')

<div class="filter-bar" style="align-items: center;">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search rooms..." id="searchInput">
    </div>
    <div style="display: flex; gap: 12px; margin-left: auto; align-items: center;">
        <select id="statusFilter" style="width: auto;">
            <option value="">All Status</option>
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="maintenance">Maintenance</option>
        </select>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-gold">
            <i class="fas fa-plus"></i> Add Room
        </a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">All Rooms</div>
        <span style="font-size:12px;color:#AAA;">{{ $rooms->total() ?? 0 }} rooms total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Room No.</th>
                <th>Type</th>
                <th>Floor</th>
                <th>Price / Night</th>
                <th>Capacity</th>
                <th>Amenities</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
            <tr>
                <td><strong>{{ $room->room_number }}</strong></td>
                <td>{{ $room->room_type }}</td>
                <td>Floor {{ $room->floor_number }}</td>
                <td>₱{{ number_format($room->price_per_night, 2) }}</td>
                <td>{{ $room->capacity }} pax</td>
                <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#888;font-size:12px;">{{ $room->amenities ?? '—' }}</td>
                <td>
                    @if($room->status === 'available')
                        <span class="badge badge-green">Available</span>
                    @elseif($room->status === 'occupied')
                        <span class="badge badge-red">Occupied</span>
                    @elseif($room->status === 'maintenance')
                        <span class="badge badge-yellow">Maintenance</span>
                    @else
                        <span class="badge badge-grey">{{ ucfirst($room->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.rooms.edit', $room->room_id) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.rooms.destroy', $room->room_id) }}" onsubmit="return confirm('Delete this room?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-door-open"></i><p>No rooms found</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $rooms->links() }}</div>
</div>

@endsection