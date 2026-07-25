@forelse($enrollments as $enrollment)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    @if($enrollment->user->profile_photo_url)
                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ $enrollment->user->profile_photo_url }}" alt="">
                    @else
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                            {{ strtoupper(substr($enrollment->user->names, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $enrollment->user->names }}</div>
                    <div class="text-sm text-gray-500">DNI: {{ $enrollment->user->dni ?? 'N/A' }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $enrollment->user->email }}</div>
            <div class="text-sm text-gray-500">{{ $enrollment->user->phone ?? 'Sin teléfono' }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('d/m/Y h:i A') : $enrollment->created_at->format('d/m/Y h:i A') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-2">
                <div class="w-full bg-gray-200 rounded-full h-2 max-w-[100px]">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                </div>
                <span class="text-xs font-medium text-gray-700">{{ number_format($enrollment->progress ?? 0, 0) }}%</span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($enrollment->status === 'completed')
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completado</span>
            @elseif($enrollment->status === 'active')
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Activo</span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($enrollment->status ?? 'Desconocido') }}</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-10 text-center">
            <div class="flex flex-col items-center justify-center text-gray-500">
                <i class="bi bi-person-check text-4xl mb-3 text-gray-300"></i>
                <p class="text-lg font-medium">No se encontraron estudiantes</p>
                <p class="text-sm">Tu búsqueda no dio resultados.</p>
            </div>
        </td>
    </tr>
@endforelse