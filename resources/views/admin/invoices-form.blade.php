@extends('admin.layout')
@section('title', isset($invoice) ? 'Edit Invoice' : 'Record Invoice')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Invoices
    </a>
</div>

<div class="section-card">
    <div class="section-header">
        <div class="section-title">{{ isset($invoice) ? 'Edit Invoice' : 'Record New Invoice' }}</div>
    </div>
    
    <div style="padding:28px 24px;">
        <form method="POST" action="{{ isset($invoice) ? route('admin.invoices.update', $invoice->invoice_id) : route('admin.invoices.store') }}">
            @csrf
            @if(isset($invoice)) @method('PUT') @endif

            <div class="form-grid">
                
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Link to Reservation <span style="color:#C9A84C">*</span></label>
                    <select name="reservation_id" id="reservation_id" required>
                        <option value="">Select a reservation...</option>
                        @foreach($reservations as $res)
                            <option value="{{ $res->reservation_id }}" 
                                data-guest="{{ $res->guest_id }}"
                                {{ old('reservation_id', $invoice->reservation_id ?? '') === $res->reservation_id ? 'selected' : '' }}>
                                Res: {{ strtoupper(substr($res->reservation_id, 0, 8)) }} - {{ $res->guest->first_name }} {{ $res->guest->last_name }} ({{ $res->room->room_number ?? 'Room' }})
                            </option>
                        @endforeach
                    </select>
                    @error('reservation_id')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="display:none;" id="guest_group">
                    <label>Guest <span style="color:#C9A84C">*</span></label>
                    <select name="guest_id" id="guest_id" required>
                        <option value="">Select guest...</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->guest_id }}" {{ old('guest_id', $invoice->guest_id ?? '') === $guest->guest_id ? 'selected' : '' }}>
                                {{ $guest->first_name }} {{ $guest->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('guest_id')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <!-- AMOUNTS -->
                <div class="form-group">
                    <label>Subtotal (₱) <span style="color:#C9A84C">*</span></label>
                    <input type="number" step="0.01" name="subtotal" id="subtotal" value="{{ old('subtotal', $invoice->subtotal ?? '') }}" required placeholder="0.00">
                    @error('subtotal')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Tax Amount (₱)</label>
                    <input type="number" step="0.01" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', $invoice->tax_amount ?? '0.00') }}" placeholder="0.00">
                    @error('tax_amount')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Discount (₱)</label>
                    <input type="number" step="0.01" name="discount" id="discount" value="{{ old('discount', $invoice->discount ?? '0.00') }}" placeholder="0.00">
                    @error('discount')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Total Amount (₱) <span style="color:#C9A84C">*</span></label>
                    <input type="number" step="0.01" name="total_amount" id="total_amount" value="{{ old('total_amount', $invoice->total_amount ?? '') }}" required placeholder="0.00" readonly style="background:#FAF8F3;">
                    @error('total_amount')<span style="color:#C0392B;font-size:12px;">{{ $message }}</span>@enderror
                </div>

                <!-- DATES & STATUS -->
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['pending', 'paid', 'overdue', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $invoice->status ?? 'pending') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Issued At</label>
                    <input type="datetime-local" name="issued_at" value="{{ old('issued_at', isset($invoice->issued_at) ? \Carbon\Carbon::parse($invoice->issued_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', isset($invoice->due_date) ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : now()->addDays(7)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px;">
                <button type="submit" class="btn btn-gold">
                    <i class="fas fa-save"></i> {{ isset($invoice) ? 'Update Invoice' : 'Record Invoice' }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 20px; }
.form-group { display: flex; flex-direction: column; }
.form-group label { font-size: 12px; font-weight: 600; color: #2C2C2C; margin-bottom: 8px; letter-spacing: 0.5px; }
.form-group input,
.form-group select { padding: 11px 14px; border: 1px solid #EAE6DF; border-radius: 6px; font-size: 13px; font-family: inherit; background: #FFF; }
.form-group input:focus,
.form-group select:focus { outline: none; border-color: #C9A84C; box-shadow: 0 0 0 2px rgba(201, 168, 76, 0.1); }
</style>

@push('scripts')
<script>
    // Auto-select guest based on reservation
    document.getElementById('reservation_id').addEventListener('change', function() {
        var selectedOpt = this.options[this.selectedIndex];
        var guestId = selectedOpt.getAttribute('data-guest');
        if(guestId) {
            document.getElementById('guest_id').value = guestId;
        }
    });

    // Auto-calculate total
    const subtotal = document.getElementById('subtotal');
    const tax = document.getElementById('tax_amount');
    const discount = document.getElementById('discount');
    const total = document.getElementById('total_amount');

    function calculateTotal() {
        let s = parseFloat(subtotal.value) || 0;
        let t = parseFloat(tax.value) || 0;
        let d = parseFloat(discount.value) || 0;
        let result = s + t - d;
        total.value = result.toFixed(2);
    }

    subtotal.addEventListener('input', calculateTotal);
    tax.addEventListener('input', calculateTotal);
    discount.addEventListener('input', calculateTotal);

    // Initial select for edit mode
    window.addEventListener('load', function() {
        if(document.getElementById('reservation_id').value && !document.getElementById('guest_id').value) {
            let opt = document.getElementById('reservation_id').options[document.getElementById('reservation_id').selectedIndex];
            document.getElementById('guest_id').value = opt.getAttribute('data-guest');
        }
    });
</script>
@endpush

@endsection
