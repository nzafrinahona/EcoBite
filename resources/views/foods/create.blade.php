<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.food-items.index') }}" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Add Food Item</h1>
                <p class="text-sm text-slate-500 mt-0.5">List a new surplus food item for students</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <p class="font-semibold mb-2">Please fix these errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.food-items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" required
                           class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
                    <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1.5">Discounted Price ($) <span class="text-red-500">*</span></label>
                        <input id="price" name="price" type="number" step="0.01" min="0.01" value="{{ old('price') }}" required
                               class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
                        <x-input-error :messages="$errors->get('price')" class="mt-1.5" />
                    </div>
                    <div>
                        <label for="original_price" class="block text-sm font-medium text-slate-700 mb-1.5">Original Price ($)</label>
                        <input id="original_price" name="original_price" type="number" step="0.01" min="0.01" value="{{ old('original_price') }}"
                               class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
                        <x-input-error :messages="$errors->get('original_price')" class="mt-1.5" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1.5">Quantity <span class="text-red-500">*</span></label>
                        <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity') }}" required
                               class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
                        <x-input-error :messages="$errors->get('quantity')" class="mt-1.5" />
                    </div>
                    <div>
                        <label for="expiry_time" class="block text-sm font-medium text-slate-700 mb-1.5">Expiry Time <span class="text-red-500">*</span></label>
                        <input id="expiry_time" name="expiry_time" type="datetime-local" value="{{ old('expiry_time') }}" required
                               class="w-full rounded-xl border-slate-200 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm">
                        <x-input-error :messages="$errors->get('expiry_time')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-slate-700 mb-1.5">Image (optional)</label>
                    <input id="image" name="image" type="file" accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <x-input-error :messages="$errors->get('image')" class="mt-1.5" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('staff.food-items.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
                        Add Food Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>