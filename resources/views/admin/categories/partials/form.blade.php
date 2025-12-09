
@csrf
@method('POST')
<div class="space-y-4">
    <!-- Nombre -->
    <input type="hidden" name="id" id="id" value="{{ $category->id ?? '' }}">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre *</label>
        <input type="text"
            name="name"
            id="name"
            value="{{ old('name', $category->name ?? '') }}"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <!-- Descripción -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <!-- Estado -->
    <div>
        <input type="hidden" name="is_active" value="0">
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ (isset($category) && $category->is_active) || old('is_active') ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                Categoría activa
            </label>
        </div>
        @error('is_active')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
