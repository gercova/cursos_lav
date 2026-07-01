@extends('layouts.admin')
@section('title', 'Cronograma de Capacitaciones')

@section('content')
<div class="space-y-6" x-data="scheduleManager()" x-init="init()">

    {{-- ── CABECERA ──────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Cronograma de Capacitaciones
            </h1>
            <p class="text-gray-500 mt-1 text-sm">
                Programa los cursos por mes y empresa. Los cursos se activan automáticamente al llegar su mes.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="openAddModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors duration-200 shadow-sm">
                <i class="fas fa-plus"></i> Agregar al Cronograma
            </button>
            <button @click="confirmCopyYear()"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors duration-200 shadow-sm">
                <i class="fas fa-copy"></i> Copiar al {{ $year + 1 }}
            </button>
        </div>
    </div>

    {{-- ── FILTROS AÑO / EMPRESA ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-wrap gap-4 items-center">
        <div class="flex items-center gap-2">
            <label class="text-sm font-semibold text-gray-600">Año:</label>
            <div class="flex items-center gap-1">
                <a href="{{ request()->fullUrlWithQuery(['year' => $year - 1]) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600 transition-colors">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
                <span class="text-lg font-bold text-blue-700 px-3">{{ $year }}</span>
                <a href="{{ request()->fullUrlWithQuery(['year' => $year + 1]) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600 transition-colors">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <label class="text-sm font-semibold text-gray-600">Empresa:</label>
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="year" value="{{ $year }}">
                <select name="company_code" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">— Todas las empresas (global) —</option>
                    @foreach($companyCodes as $code)
                        <option value="{{ $code }}" {{ $filterCode == $code ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="ml-auto flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Liberado
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span> Próximo
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded-full bg-gray-300 inline-block"></span> Global (todas las empresas)
            </span>
        </div>
    </div>

    {{-- ── GRILLA CRONOGRAMA ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-700 to-blue-800 text-white">
                        <th class="text-left px-4 py-3 font-semibold w-32">MES</th>
                        <th class="text-left px-4 py-3 font-semibold">CURSOS PROGRAMADOS</th>
                        <th class="text-center px-4 py-3 font-semibold w-24">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($months as $num => $name)
                        @php
                            $now     = now();
                            $isPast  = ($year < $now->year) || ($year == $now->year && $num < $now->month);
                            $isCurrent = ($year == $now->year && $num == $now->month);
                            $items   = $byMonth->get($num, collect());
                        @endphp
                        <tr class="group {{ $isCurrent ? 'bg-blue-50' : ($isPast ? 'bg-gray-50/50' : 'bg-white') }} hover:bg-blue-50/40 transition-colors duration-150">
                            {{-- Columna MES --}}
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center gap-2">
                                    @if($isCurrent)
                                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse flex-shrink-0"></span>
                                    @elseif($isPast)
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-gray-300 flex-shrink-0"></span>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $name }}</p>
                                        <p class="text-xs text-gray-400">{{ $year }}</p>
                                    </div>
                                    @if($isCurrent)
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium ml-1">Actual</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Columna CURSOS --}}
                            <td class="px-4 py-3 align-top">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($items as $item)
                                         <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium border
                                             {{ !$item->is_active
                                                 ? 'bg-gray-50 border-gray-200 text-gray-500'
                                                 : ($item->is_released
                                                     ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                                                     : 'bg-blue-50 border-blue-200 text-blue-800') }}
                                             relative group/item shadow-sm">
                                             
                                             {{-- Botón toggle (Activar/Desactivar) --}}
                                             <button @click="toggleActive({{ $item->id }}, {{ $item->is_active ? 'false' : 'true' }})"
                                                 class="flex-shrink-0 hover:scale-110 transition-transform focus:outline-none"
                                                 title="{{ $item->is_active ? 'Desactivar (Ocultar por falta de pago)' : 'Activar (Mostrar)' }}">
                                                 <i class="fas {{ $item->is_active ? 'fa-toggle-on text-emerald-500 text-lg' : 'fa-toggle-off text-gray-400 text-lg' }}"></i>
                                             </button>

                                             <div class="flex flex-col {{ !$item->is_active ? 'line-through opacity-70' : '' }}">
                                                 <span class="max-w-[200px] truncate font-bold" title="{{ $item->course?->title }}">
                                                     {{ $item->course?->title ?? 'Curso eliminado' }}
                                                 </span>
                                                 <div class="flex gap-1 mt-0.5 items-center">
                                                     @if($item->company_code)
                                                         <span class="text-[10px] bg-white/80 px-1.5 py-0.5 rounded border border-current opacity-80 font-semibold">
                                                             {{ $item->company_code }}
                                                         </span>
                                                     @else
                                                         <span class="text-[10px] bg-white/80 px-1.5 py-0.5 rounded border border-current opacity-80 font-semibold">
                                                             Global
                                                         </span>
                                                     @endif
                                                     @if($item->modality)
                                                         <span class="opacity-70 text-[10px]">· {{ $item->modality }}</span>
                                                     @endif
                                                 </div>
                                             </div>

                                             {{-- Contenedor de acciones (Editar / Eliminar) --}}
                                             <div class="ml-auto flex items-center gap-1">
                                                 {{-- Botón editar --}}
                                                 <button @click="editItem({
                                                     id: {{ $item->id }},
                                                     course_id: {{ $item->course_id }},
                                                     month: {{ $item->month }},
                                                     year: {{ $item->year }},
                                                     company_code: '{{ $item->company_code ?? '' }}',
                                                     modality: '{{ $item->modality ?? '' }}',
                                                     responsible_area: '{{ $item->responsible_area ?? '' }}',
                                                     scope: '{{ $item->scope ?? '' }}',
                                                     notes: '{{ addslashes($item->notes ?? '') }}',
                                                     is_active: {{ $item->is_active ? 'true' : 'false' }}
                                                 })"
                                                     class="w-6 h-6 flex items-center justify-center rounded bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors flex-shrink-0"
                                                     title="Editar programacion">
                                                     <i class="fas fa-pencil-alt text-xs"></i>
                                                 </button>

                                                 {{-- Botón eliminar --}}
                                                 <button @click="deleteItem({{ $item->id }}, '{{ addslashes($item->course?->title ?? '') }}')"
                                                     class="w-6 h-6 flex items-center justify-center rounded bg-red-100 text-red-500 hover:bg-red-600 hover:text-white transition-colors flex-shrink-0"
                                                     title="Eliminar del cronograma">
                                                     <i class="fas fa-trash text-xs"></i>
                                                 </button>
                                             </div>
                                         </div>
                                     @empty
                                         <span class="text-xs text-gray-400 italic">Sin cursos programados</span>
                                     @endforelse                                </div>
                            </td>

                            {{-- Columna ACCIONES --}}
                            <td class="px-4 py-3 align-top text-center">
                                <button @click="openAddModal({{ $num }}, {{ $year }})"
                                    class="text-blue-600 hover:text-blue-700 opacity-0 group-hover:opacity-100 transition-opacity text-sm"
                                    title="Agregar curso a {{ $name }}">
                                    <i class="fas fa-plus-circle text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── RESUMEN ───────────────────────────────────────────────────────── --}}
    @php
        $totalItems    = $byMonth->flatten()->count();
        $releasedCount = $byMonth->flatten()->filter(fn($i) => $i->is_released)->count();
        $upcomingCount = $totalItems - $releasedCount;
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-check text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total programado</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalItems }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Ya liberados</p>
                <p class="text-xl font-bold text-gray-800">{{ $releasedCount }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Próximos</p>
                <p class="text-xl font-bold text-gray-800">{{ $upcomingCount }}</p>
            </div>
        </div>
    </div>

    {{-- ── MODAL: AGREGAR AL CRONOGRAMA ─────────────────────────────────── --}}
    <div x-show="showAddModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas text-blue-600 mr-2" :class="isEditMode ? 'fa-calendar-check text-emerald-600' : 'fa-calendar-plus text-blue-600'"></i>
                    <span x-text="isEditMode ? 'Editar Curso Programado' : 'Agregar Curso al Cronograma'"></span>
                </h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="submitAdd()" class="p-5 space-y-4">
                {{-- Curso --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Curso <span class="text-red-500">*</span>
                    </label>
                    <select x-model="form.course_id" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Seleccionar curso —</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Mes / Año --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Mes <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.month" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— Mes —</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Año <span class="text-red-500">*</span>
                        </label>
                        <input type="number" x-model="form.year" min="2024" max="2100" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Código de empresa --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Empresa (dejar vacío = todas)
                    </label>
                    <select x-model="form.company_code"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Global (todas las empresas) —</option>
                        @foreach($companyCodes as $code)
                            <option value="{{ $code }}">{{ $code }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Modalidad / Área --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Modalidad</label>
                        <select x-model="form.modality"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— Opcional —</option>
                            <option value="Virtual">Virtual</option>
                            <option value="Presencial">Presencial</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Área responsable</label>
                        <input type="text" x-model="form.responsible_area" placeholder="SST, ENFERMERO…"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Alcance --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alcance</label>
                    <input type="text" x-model="form.scope" placeholder="Todos, Personal nuevo, Brigadistas…"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Notas --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notas</label>
                    <textarea x-model="form.notes" rows="2" placeholder="Observaciones adicionales…"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                {{-- Estado de Visibilidad (Suscripción) --}}
                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200/80">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Visibilidad del Curso (Suscripción)
                        </label>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Ocular si la empresa no ha pagado la cuota mensual.
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                {{-- Alerta de error --}}
                <div x-show="errorMsg" class="bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2 rounded-lg" x-text="errorMsg"></div>

                {{-- Botones --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                        class="flex-1 border border-gray-200 text-gray-700 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="saving"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 disabled:opacity-60">
                        <i class="fas fa-spinner fa-spin" x-show="saving"></i>
                        <i class="fas fa-save" x-show="!saving"></i>
                        <span x-text="saving ? 'Guardando…' : 'Guardar'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: CONFIRMAR ELIMINAR ─────────────────────────────────────── --}}
    <div x-show="showDeleteModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition>
        <div class="absolute inset-0 bg-black/50" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">¿Eliminar del cronograma?</h3>
            <p class="text-sm text-gray-500 mb-6">
                Vas a eliminar <strong x-text="deleteTarget.title" class="text-gray-800"></strong> del cronograma.
                Esta acción no puede deshacerse.
            </p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false"
                    class="flex-1 border border-gray-200 text-gray-700 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button @click="confirmDelete()" :disabled="saving"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-spinner fa-spin" x-show="saving"></i>
                    Eliminar
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
function scheduleManager() {
    return {
        // Estado modales
        showAddModal:    false,
        showDeleteModal: false,
        saving:          false,
        errorMsg:        '',
        isEditMode:      false,
        // Formulario
        form: {
            id:               null,
            course_id:        '',
            month:            '',
            year:             {{ $year }},
            company_code:     '{{ $filterCode ?? '' }}',
            modality:         '',
            responsible_area: '',
            scope:            '',
            notes:            '',
            is_active:        true,
        },

        // Para eliminar
        deleteTarget: { id: null, title: '' },

        init() {
            // Nada que inicializar
        },

        openAddModal(month = null, year = null) {
            this.isEditMode            = false;
            this.errorMsg              = '';
            this.form.id               = null;
            this.form.course_id        = '';
            this.form.month            = month ?? '';
            this.form.year             = year  ?? {{ $year }};
            this.form.company_code     = '{{ $filterCode ?? '' }}';
            this.form.modality         = '';
            this.form.responsible_area = '';
            this.form.scope            = '';
            this.form.notes            = '';
            this.form.is_active        = true;
            this.showAddModal          = true;
        },

        editItem(item) {
            this.isEditMode            = true;
            this.errorMsg              = '';
            this.form.id               = item.id;
            this.form.course_id        = item.course_id;
            this.form.month            = item.month;
            this.form.year             = item.year;
            this.form.company_code     = item.company_code || '';
            this.form.modality         = item.modality || '';
            this.form.responsible_area = item.responsible_area || '';
            this.form.scope            = item.scope || '';
            this.form.notes            = item.notes || '';
            this.form.is_active        = !!item.is_active;
            this.showAddModal          = true;
        },

        async submitAdd() {
            if (!this.form.course_id || !this.form.month || !this.form.year) {
                this.errorMsg = 'Por favor completa los campos obligatorios.';
                return;
            }
            this.saving   = true;
            this.errorMsg = '';
            try {
                let res;
                if (this.isEditMode) {
                    res = await axios.put(`{{ url('admin/schedules') }}/${this.form.id}`, this.form, {
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                } else {
                    res = await axios.post('{{ route('admin.schedules.store') }}', this.form, {
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                }
                if (res.data.success) {
                    this.showAddModal = false;
                    window.location.reload();
                } else {
                    this.errorMsg = res.data.message ?? 'Error al guardar.';
                }
            } catch (err) {
                this.errorMsg = err.response?.data?.message ?? 'Error de servidor.';
            } finally {
                this.saving = false;
            }
        },

        async toggleActive(id, newStatus) {
            this.saving = true;
            try {
                const res = await axios.put(`{{ url('admin/schedules') }}/${id}`, {
                    is_active: newStatus
                }, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (res.data.success) {
                    window.location.reload();
                } else {
                    alert('Error al actualizar el estado.');
                }
            } catch (err) {
                console.error(err);
                alert('Error de servidor al actualizar el estado.');
            } finally {
                this.saving = false;
            }
        },

        deleteItem(id, title) {
            this.deleteTarget = { id, title };
            this.showDeleteModal = true;
        },

        async confirmDelete() {
            this.saving = true;
            try {
                const res = await axios.delete(`{{ url('admin/schedules') }}/${this.deleteTarget.id}`, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (res.data.success) {
                    this.showDeleteModal = false;
                    window.location.reload();
                }
            } catch {
                alert('Error al eliminar.');
            } finally {
                this.saving = false;
            }
        },

        async confirmCopyYear() {
            const ok = confirm('¿Copiar todo el cronograma de {{ $year }} al año {{ $year + 1 }}? Solo se copian los items que no existan ya.');
            if (!ok) return;
            try {
                const res = await axios.post('{{ route('admin.schedules.copy-year') }}', {
                    from_year:    {{ $year }},
                    company_code: '{{ $filterCode ?? '' }}',
                }, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                alert(res.data.message);
                window.location.href = '{{ route('admin.schedules.index') }}?year={{ $year + 1 }}{{ $filterCode ? "&company_code=$filterCode" : "" }}';
            } catch {
                alert('Error al copiar.');
            }
        },
    };
}</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
