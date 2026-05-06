@extends('staff.layouts.app')
@section('title', 'Create Room')
@section('page-title', 'Create Room')

@section('content')
<div class="flex justify-center">
    <div class="bg-white rounded-xl shadow-sm p-8 w-full max-w-2xl">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Room Details</h2>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('staff.rooms.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Image Upload --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Room Image</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-yellow-400 transition"
                     onclick="document.getElementById('imageInput').click()">
                    <img id="imagePreview" src="" alt="" class="hidden w-full h-48 object-cover rounded-lg mb-3 mx-auto">
                    <div id="uploadPlaceholder">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Click to upload room image</p>
                        <p class="text-xs text-gray-300 mt-1">PNG, JPG up to 2MB</p>
                    </div>
                    <input type="file" id="imageInput" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
                    <input type="text" name="room_number" value="{{ old('room_number') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2"
                        placeholder="e.g. 101">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                    <select name="room_type" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none">
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                        <option value="suite">Suite</option>
                        <option value="deluxe">Deluxe</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Floor Number</label>
                    <input type="number" name="floor_number" value="{{ old('floor_number') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                        placeholder="e.g. 1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Per Night (₱)</label>
                    <input type="number" name="price_per_night" step="0.01" value="{{ old('price_per_night') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                        placeholder="e.g. 2500.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                        placeholder="e.g. 2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                    <textarea name="amenities" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"
                        placeholder="e.g. WiFi, AC, TV, Mini-bar">{{ old('amenities') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit"
                    class="px-6 py-3 rounded-lg text-white text-sm font-medium hover:opacity-90"
                    style="background:#b8972e">
                    Save Room
                </button>
                <a href="{{ route('staff.rooms.index') }}"
                    class="px-6 py-3 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('uploadPlaceholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection