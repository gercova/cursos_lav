@if($courses->count() > 0)
    <!-- Grid View Container -->
    <div id="grid-view-container" class="courses-view">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            @foreach($courses as $course)
                @php
                    $finalPrice  = $course->promotion_price ?? $course->price;
                    $hasPromo    = $course->promotion_price && $course->promotion_price < $course->price;
                    $discount    = $hasPromo ? round((($course->price - $course->promotion_price) / $course->price) * 100) : 0;
                    $levelColors = [
                        'basico'     => 'bg-emerald-500',
                        'intermedio' => 'bg-amber-500',
                        'avanzado'   => 'bg-rose-500'
                    ];
                    $levelColor  = $levelColors[strtolower($course->level ?? '')] ?? 'bg-slate-500';
                @endphp
                <div class="course-card group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-1.5"
                     data-category="{{ $course->category_id }}"
                     data-price="{{ $finalPrice }}"
                     data-date="{{ $course->created_at->timestamp }}"
                     data-popularity="{{ $course->enrollments_count ?? 0 }}">

                    <!-- Thumbnail Area -->
                    <a href="{{ route('course.show', ['slug' => $course->slug, 'code' => $code ?? null]) }}" class="block relative overflow-hidden aspect-video">
                        @if($course->image_url)
                            <img src="{{ $course->image_url }}"
                                 alt="{{ $course->title }}"
                                 loading="lazy"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Hover Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Top Floating Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($course->level)
                                <span class="{{ $levelColor }} text-white text-[10px] font-extrabold uppercase tracking-wide px-2 py-0.5 rounded shadow-sm">
                                    {{ ucfirst($course->level) }}
                                </span>
                            @endif
                        </div>

                        @if($hasPromo)
                            <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md animate-pulse">
                                -{{ $discount }}%
                            </span>
                        @endif

                        <!-- Category Badge bottom left -->
                        <span class="absolute bottom-3 left-3 bg-blue-600/90 backdrop-blur-sm text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg shadow-sm">
                            {{ $course->category->name ?? 'Cursos' }}
                        </span>

                        <!-- Action indicator -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="bg-white/95 backdrop-blur-sm text-blue-700 text-xs font-bold px-4 py-2 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                Ver Curso &rarr;
                            </span>
                        </div>
                    </a>

                    <!-- Details Area -->
                    <div class="p-5 flex flex-col flex-1">
                        <!-- Title -->
                        <h3 class="font-bold text-gray-900 text-base leading-snug mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                            <a href="{{ route('course.show', ['slug' => $course->slug, 'code' => $code ?? null]) }}">
                                {{ $course->title }}
                            </a>
                        </h3>

                        <!-- Short Description -->
                        <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-4 flex-1">
                            {{ $course->short_description ?: Str::limit($course->description, 100) }}
                        </p>

                        <!-- Meta Info (Modules & Lessons) -->
                        <div class="flex items-center gap-4 text-[11px] text-gray-500 mb-4 bg-gray-50 p-2 rounded-lg">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>{{ $course->sections->count() }} {{ $course->sections->count() === 1 ? 'Módulo' : 'Módulos' }}</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'Lección' : 'Lecciones' }}</span>
                            </span>
                        </div>

                        <!-- Instructor Profile -->
                        <div class="flex items-center gap-2.5 mb-4 pb-4 border-b border-gray-100">
                            <img class="h-8 w-8 rounded-full object-cover ring-2 ring-blue-50/50 flex-shrink-0"
                                 src="{{ $course->instructor->profile_photo ? Storage::url($course->instructor->profile_photo) : asset('storage/instructors/instructor-default.png') }}"
                                 alt="{{ $course->instructor->names }}">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate leading-none mb-0.5">{{ $course->instructor->names }}</p>
                                <p class="text-[10px] text-gray-400 truncate leading-none">{{ $course->instructor->profession ?? 'Instructor Experto' }}</p>
                            </div>
                        </div>

                        <!-- Pricing and Add Button -->
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex flex-col leading-none">
                                @if($hasPromo)
                                    <span class="text-lg font-extrabold text-blue-600">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                    <span class="text-xs text-gray-400 line-through mt-0.5">S/ {{ number_format($course->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-extrabold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                @endif
                            </div>

                            <button onclick="addToCart({{ $course->id }}, event)"
                                    class="add-to-cart-btn flex-shrink-0 flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- List View Container (Hidden by default) -->
    <div id="list-view-container" class="courses-view hidden">
        <div class="space-y-6">
            @foreach($courses as $course)
                @php
                    $finalPrice  = $course->promotion_price ?? $course->price;
                    $hasPromo    = $course->promotion_price && $course->promotion_price < $course->price;
                    $discount    = $hasPromo ? round((($course->price - $course->promotion_price) / $course->price) * 100) : 0;
                    $levelColors = [
                        'basico'     => 'bg-emerald-500',
                        'intermedio' => 'bg-amber-500',
                        'avanzado'   => 'bg-rose-500'
                    ];
                    $levelColor  = $levelColors[strtolower($course->level ?? '')] ?? 'bg-slate-500';
                @endphp
                <div class="course-card bg-white border border-gray-100 rounded-2xl hover:shadow-xl transition-all duration-300 overflow-hidden"
                     data-category="{{ $course->category_id }}"
                     data-price="{{ $finalPrice }}"
                     data-date="{{ $course->created_at->timestamp }}"
                     data-popularity="{{ $course->enrollments_count ?? 0 }}">
                    <div class="flex flex-col md:flex-row">
                        <!-- Thumbnail Column -->
                        <div class="w-full md:w-72 md:flex-shrink-0 relative aspect-video md:aspect-auto">
                            <a href="{{ route('course.show', ['slug' => $course->slug, 'code' => $code ?? null]) }}" class="block w-full h-full min-h-[180px]">
                                @if($course->image_url)
                                    <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full min-h-[180px] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Overlays -->
                                <div class="absolute inset-0 bg-black/10"></div>
                                <div class="absolute top-3 left-3 flex flex-col gap-1">
                                    @if($course->level)
                                        <span class="{{ $levelColor }} text-white text-[10px] font-extrabold uppercase tracking-wide px-2 py-0.5 rounded shadow">
                                            {{ ucfirst($course->level) }}
                                        </span>
                                    @endif
                                </div>
                                @if($hasPromo)
                                    <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md animate-pulse">
                                        -{{ $discount }}%
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Content Column -->
                        <div class="flex-1 p-5 sm:p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                        {{ $course->category->name }}
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        {{ $course->sections->count() }} {{ $course->sections->count() === 1 ? 'Módulo' : 'Módulos' }}
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'Lección' : 'Lecciones' }}
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors">
                                    <a href="{{ route('course.show', ['slug' => $course->slug, 'code' => $code ?? null]) }}">
                                        {{ $course->title }}
                                    </a>
                                </h3>

                                <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3 md:line-clamp-2">
                                    {{ $course->description }}
                                </p>
                            </div>

                            <!-- Bottom Row -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-100">
                                <!-- Instructor profile -->
                                <div class="flex items-center gap-2.5">
                                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-50/50 flex-shrink-0"
                                         src="{{ $course->instructor->profile_photo ? Storage::url($course->instructor->profile_photo) : asset('storage/instructors/instructor-default.png') }}"
                                         alt="{{ $course->instructor->names }}">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 leading-tight">{{ $course->instructor->names }}</p>
                                        <p class="text-[10px] text-gray-400 leading-tight">{{ $course->instructor->profession ?? 'Instructor Experto' }}</p>
                                    </div>
                                </div>

                                <!-- Price & Button group -->
                                <div class="flex items-center justify-between sm:justify-end gap-6">
                                    <div class="flex flex-col items-end leading-none">
                                        @if($hasPromo)
                                            <span class="text-2xl font-extrabold text-blue-600">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                            <span class="text-xs text-gray-400 line-through mt-0.5">S/ {{ number_format($course->price, 2) }}</span>
                                        @else
                                            <span class="text-2xl font-extrabold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                        @endif
                                    </div>

                                    <button onclick="addToCart({{ $course->id }}, event)"
                                            class="add-to-cart-btn bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold px-6 py-3 rounded-xl transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Agregar al carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination Links -->
    @if($courses->hasPages())
        <div class="mt-12 flex justify-center">
            <div class="bg-white px-5 py-3 rounded-xl shadow-sm border border-gray-100">
                {{ $courses->links() }}
            </div>
        </div>
    @endif
    
    <!-- Virtual dataset attribute to pass pagination counts to JS -->
    <div id="pagination-info" data-total="{{ $courses->total() }}" class="hidden"></div>
@else
    <!-- Empty State -->
    <div id="empty-state" class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-5">
            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No se encontraron cursos</h3>
        <p class="text-gray-500 mb-6 max-w-sm mx-auto text-sm">Intenta ajustar los filtros de búsqueda o categoría para encontrar lo que necesitas.</p>
        <button onclick="coursesPage.clearFilters()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-sm text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Mostrar todos los cursos
        </button>
    </div>
@endif
