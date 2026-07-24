@extends('layouts.app')
@section('title', ($enterprise->trade_name ?? 'IFP Educa') . ' - Sobre Nosotros')
@section('meta')
    <meta name="description" content="Conoce la historia, misión, visión y valores de {{ $enterprise->trade_name ?? 'IFP Educa' }}. Somos la institución líder en capacitación y certificación en Seguridad y Salud en el Trabajo (SST), Calidad y Medio Ambiente en el Perú.">
    <meta name="keywords" content="sobre nosotros {{ $enterprise->trade_name ?? 'IFP Educa' }}, capacitacion SST peru, cursos SST, normas ISO peru, certificaciones laborales, historia {{ $enterprise->trade_name ?? 'IFP Educa' }}, seguridad ocupacional peru">
    <meta name="author" content="{{ $enterprise->trade_name ?? 'IFP Educa' }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- OpenGraph Metadata -->
    <meta property="og:locale" content="es_PE">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Sobre Nosotros | {{ $enterprise->trade_name ?? 'IFP Educa' }} - Líderes en SST y Capacitación">
    <meta property="og:description" content="Transformamos vidas y organizaciones con formación especializada en Seguridad, Salud Ocupacional, Calidad ISO y Medio Ambiente. Conoce nuestro compromiso con la excelencia.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $enterprise->trade_name ?? 'IFP Educa' }}">
    @if(isset($enterprise->logo_path))
        <meta property="og:image" content="{{ asset($enterprise->logo_path) }}">
        <meta property="og:image:alt" content="{{ $enterprise->trade_name ?? 'IFP Educa' }}">
    @endif

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Sobre Nosotros - {{ $enterprise->trade_name ?? 'IFP Educa' }}">
    <meta name="twitter:description" content="Capacitación y certificación profesional en SST, Gestión de Calidad y Medio Ambiente en Perú.">
    @if(isset($enterprise->logo_path))
        <meta name="twitter:image" content="{{ asset($enterprise->logo_path) }}">
    @endif

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "EducationalOrganization",
          "@id": "{{ url('/') }}#organization",
          "name": "{{ $enterprise->trade_name ?? 'IFP Educa' }}",
          "legalName": "{{ $enterprise->company_name ?? ($enterprise->trade_name ?? 'IFP Educa') }}",
          "url": "{{ url('/') }}",
          "logo": "{{ asset($enterprise->logo_path ?? '') }}",
          "description": "{{ $enterprise->description ?? 'Plataforma líder en capacitación y certificación en Seguridad y Salud en el Trabajo (SST), Gestión de Calidad y Medio Ambiente en Perú.' }}",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $enterprise->address ?? 'Lima, Perú' }}",
            "addressLocality": "{{ $enterprise->city ?? 'Lima' }}",
            "addressCountry": "PE"
          },
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "{{ $enterprise->phone_number_1 ?? '+51 900 000 000' }}",
            "contactType": "customer service",
            "email": "{{ $enterprise->email ?? 'contacto@ipf-educa.com' }}",
            "availableLanguage": "Spanish"
          },
          "sameAs": [
            @if(!empty($enterprise->facebook_link)) "{{ $enterprise->facebook_link }}", @endif
            @if(!empty($enterprise->linkedin_link)) "{{ $enterprise->linkedin_link }}", @endif
            @if(!empty($enterprise->instagram_link)) "{{ $enterprise->instagram_link }}", @endif
            @if(!empty($enterprise->twitter_link)) "{{ $enterprise->twitter_link }}" @endif
          ]
        },
        {
          "@type": "BreadcrumbList",
          "@id": "{{ url()->current() }}#breadcrumb",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Inicio",
              "item": "{{ url('/') }}"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Sobre Nosotros",
              "item": "{{ url()->current() }}"
            }
          ]
        }
      ]
    }
    </script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 py-20 lg:py-28 overflow-hidden">
    <!-- Dynamic Decorative background shapes -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 animate-fade-in">
            Impulsamos la Excelencia Profesional y la <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-indigo-300">Seguridad Ocupacional</span>
        </h1>

        <p class="text-lg sm:text-xl lg:text-2xl text-blue-100/90 max-w-3xl mx-auto font-light leading-relaxed mb-10 animate-slide-up">
            {{ $enterprise->phrase ?? 'Transformando la educación corporativa y profesional con pasión, innovación tecnológica y rigor técnico en Perú.' }}
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('cursos') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold px-7 py-3.5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="bi bi-journal-bookmark-fill text-lg"></i>
                Explorar Cursos
            </a>
            <a href="{{ url('contacto') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-md font-semibold px-7 py-3.5 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="bi bi-envelope-check-fill text-lg"></i>
                Contáctanos
            </a>
        </div>
    </div>
</section>

<!-- Stats Grid Component -->
<section class="relative -mt-10 z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 bg-white rounded-2xl shadow-xl p-6 sm:p-8 border border-gray-100">
        <div class="flex items-center gap-4 p-3 rounded-xl bg-blue-50/50 hover:bg-blue-50 transition-colors">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">5+</div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Años de Trayectoria</div>
            </div>
        </div>

        <div class="flex items-center gap-4 p-3 rounded-xl bg-emerald-50/50 hover:bg-emerald-50 transition-colors">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">10,000+</div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Certificados Emitidos</div>
            </div>
        </div>

        <div class="flex items-center gap-4 p-3 rounded-xl bg-purple-50/50 hover:bg-purple-50 transition-colors">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-purple-600 text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                <i class="bi bi-journal-check"></i>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">50+</div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Cursos Especializados</div>
            </div>
        </div>

        <div class="flex items-center gap-4 p-3 rounded-xl bg-amber-50/50 hover:bg-amber-50 transition-colors">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-amber-600 text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900">200+</div>
                <div class="text-xs sm:text-sm font-medium text-gray-600">Empresas Capacitadas</div>
            </div>
        </div>
    </div>
</section>

<!-- Company History & Overview Section -->
<section class="py-16 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Text Content -->
            <div class="lg:col-span-7 space-y-6">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Impulsando el desarrollo con <span class="text-blue-600">Compromiso y Rigor Técnico</span>
                </h2>
                
                <div class="prose prose-lg text-gray-600 space-y-4 leading-relaxed">
                    @if(!empty($enterprise->description))
                        <p class="text-gray-700 font-medium text-lg leading-relaxed">
                            {{ $enterprise->description }}
                        </p>
                    @else
                        <p>
                            En <strong class="text-gray-900">{{ $enterprise->trade_name ?? 'IFP Educa' }}</strong>, nos dedicamos a transformar la capacitación profesional en Seguridad y Salud en el Trabajo (SST), Gestión de Calidad (ISO 9001, 14001, 45001) y Medio Ambiente.
                        </p>
                    @endif
                    <p>
                        Nuestra misión es asegurar que trabajadores, profesionales y empresas peruanas cumplan con los más altos estándares normativos legales y técnicos regulados por la normativa SUNAFIL, MINTRA y organismos internacionales.
                    </p>
                    <p>
                        A través de metodologías ágiles, instructores certificados y una plataforma tecnológica moderna, brindamos programas de formación en modalidades en vivo, asíncrona e in-house diseñados para responder a los retos del mercado industrial y corporativo.
                    </p>
                </div>
            </div>

            <!-- Visual Highlight Card -->
            <div class="lg:col-span-5">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-3xl transform rotate-2 scale-105 opacity-20 blur-lg"></div>
                    <div class="relative bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-8 sm:p-10 text-white shadow-2xl overflow-hidden border border-blue-500/20">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl"></div>

                        <div class="flex items-center justify-between mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-blue-600/30 border border-blue-400/30 flex items-center justify-center text-2xl text-blue-300">
                                <i class="bi bi-bookmark-star-fill"></i>
                            </div>
                            <span class="text-xs font-semibold tracking-widest uppercase text-blue-300 bg-blue-900/60 px-3 py-1 rounded-full border border-blue-500/30">Propósito</span>
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">¿Por qué estudiar con nosotros?</h3>

                        <ul class="space-y-4 text-blue-100 text-sm sm:text-base">
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-400 text-lg flex-shrink-0 mt-0.5"></i>
                                <span><strong>Certificación Válida:</strong> Emisión de certificados con código QR de verificación instantánea.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-400 text-lg flex-shrink-0 mt-0.5"></i>
                                <span><strong>Docentes Especializados:</strong> Auditores y consultores Senior con amplia experiencia en campo.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-400 text-lg flex-shrink-0 mt-0.5"></i>
                                <span><strong>Flexibilidad 24/7:</strong> Acceso ilimitado a las clases grabadas y material complementario.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-400 text-lg flex-shrink-0 mt-0.5"></i>
                                <span><strong>Soporte Corporativo:</strong> Programas adaptados para capacitaciones anuales obligatorias Ley 29783.</span>
                            </li>
                        </ul>

                        <div class="mt-8 pt-6 border-t border-blue-800/60 flex items-center justify-between">
                            <span class="text-xs text-blue-300">Garantía de Calidad Académica</span>
                            <i class="bi bi-shield-check text-2xl text-blue-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Misión y Visión Section -->
<section class="py-16 sm:py-20 lg:py-24 bg-slate-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                Nuestra Misión y Visión
            </h2>
            <p class="mt-3 text-lg text-gray-600">
                Guiados por metas claras para elevar el estándar formativo y profesional del país.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            <!-- Card Misión -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-3xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider block">Propósito Fundamental</span>
                            <h3 class="text-2xl font-bold text-gray-900">Nuestra Misión</h3>
                        </div>
                    </div>

                    <div class="text-gray-600 leading-relaxed space-y-4">
                        @if(!empty($enterprise->mission))
                            <p class="text-base sm:text-lg">
                                {{ $enterprise->mission }}
                            </p>
                        @else
                            <p class="text-base sm:text-lg">
                                Brindar servicios de capacitación y consultoría especializada en Seguridad y Salud en el Trabajo, Gestión Ambiental y Calidad, atendiendo a nuestros clientes bajo estricto cumplimiento normativo con transparencia, rigor y atención personalizada.
                            </p>
                        @endif
                        <p class="text-sm text-gray-500">
                            Nos enfocamos en entregar herramientas prácticas que potencien las competencias de los trabajadores y fortalezcan la cultura preventiva de las organizaciones.
                        </p>
                    </div>
                </div>

                <div class="mt-8 p-4 rounded-xl bg-blue-50 border-l-4 border-blue-600 text-blue-900 text-sm font-medium italic flex items-center gap-3">
                    <i class="bi bi-quote text-2xl text-blue-600 flex-shrink-0"></i>
                    <span>"Formando profesionales capacitados para entornos laborales seguros y eficientes."</span>
                </div>
            </div>

            <!-- Card Visión -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col justify-between group">
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-3xl shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider block">Futuro e Impacto</span>
                            <h3 class="text-2xl font-bold text-gray-900">Nuestra Visión</h3>
                        </div>
                    </div>

                    <div class="text-gray-600 leading-relaxed space-y-4">
                        @if(!empty($enterprise->vision))
                            <p class="text-base sm:text-lg">
                                {{ $enterprise->vision }}
                            </p>
                        @else
                            <p class="text-base sm:text-lg">
                                Ser reconocidos como la plataforma de capacitación y consultoría líder a nivel nacional e internacional en Seguridad, Salud Ocupacional y Sistemas de Gestión ISO, siendo referentes por innovación tecnológica y excelencia académica.
                            </p>
                        @endif
                        <p class="text-sm text-gray-500">
                            Aspiramos a democratizar el aprendizaje especializado continuo, conectando a expertos y estudiantes en una comunidad orientada al crecimiento sostenible.
                        </p>
                    </div>
                </div>

                <div class="mt-8 p-4 rounded-xl bg-emerald-50 border-l-4 border-emerald-600 text-emerald-900 text-sm font-medium italic flex items-center gap-3">
                    <i class="bi bi-quote text-2xl text-emerald-600 flex-shrink-0"></i>
                    <span>"Construyendo un futuro donde el conocimiento preventivo prevenga riesgos y transforme vidas."</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nuestros Valores Corporativos Grid -->
<section class="py-16 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                Nuestros Valores Fundamentales
            </h2>
            <p class="mt-3 text-lg text-gray-600">
                Los pilares éticos y profesionales que rigen cada una de nuestras acciones y cursos.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Valor 1 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-blue-50/50 border border-gray-100 hover:border-blue-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Calidad & Excelencia</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Compromiso total con contenidos actualizados, metodologías eficientes y altos estándares educativos.
                </p>
            </div>

            <!-- Valor 2 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-emerald-50/50 border border-gray-100 hover:border-emerald-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Puntualidad</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Respeto riguroso por los tiempos de nuestros estudiantes y cronogramas corporativos.
                </p>
            </div>

            <!-- Valor 3 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-purple-50/50 border border-gray-100 hover:border-purple-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Responsabilidad</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Asumimos cada programa con seriedad profesional y enfoque directo en la seguridad de los trabajadores.
                </p>
            </div>

            <!-- Valor 4 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-amber-50/50 border border-gray-100 hover:border-amber-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-card-checklist"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Honestidad & Ética</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Transparencia absoluta en nuestras certificaciones, costos y procesos formativos.
                </p>
            </div>

            <!-- Valor 5 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-teal-50/50 border border-gray-100 hover:border-teal-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-leaf-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Sostenibilidad</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Fomentamos la gestión ambiental eficiente y el uso responsable de los recursos de nuestro entorno.
                </p>
            </div>

            <!-- Valor 6 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-indigo-50/50 border border-gray-100 hover:border-indigo-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Innovación Digital</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Utilizamos herramientas modernas de e-learning para optimizar la retención y la práctica interactiva.
                </p>
            </div>

            <!-- Valor 7 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-rose-50/50 border border-gray-100 hover:border-rose-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Comunidad</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Creación de redes profesionales y aprendizaje colaborativo entre docentes y profesionales del rubro.
                </p>
            </div>

            <!-- Valor 8 -->
            <div class="p-6 rounded-2xl bg-gray-50 hover:bg-cyan-50/50 border border-gray-100 hover:border-cyan-200 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center text-2xl mb-4 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-300">
                    <i class="bi bi-award-fill"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Integridad</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Coherencia entre nuestro discurso pedagógico y la calidad técnica de nuestros graduados.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Specialization Pillars -->
{{-- <section class="py-16 sm:py-20 lg:py-24 bg-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 text-sm font-semibold text-blue-400 uppercase tracking-wider bg-blue-900/50 border border-blue-500/30 px-3 py-1 rounded-md mb-3">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Áreas de Dominio
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                Nuestras Especializaciones Principales
            </h2>
            <p class="mt-3 text-lg text-blue-200">
                Diseñamos capacitaciones en las disciplinas de mayor exigencia y regulación en el mercado.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-800/80 rounded-2xl p-8 border border-slate-700 hover:border-blue-500/50 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-3xl mb-6">
                        <i class="bi bi-shield-shaded"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Seguridad y Salud en el Trabajo (SST)</h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Cursos y diplomados estructurados bajo la Ley N° 29783, normativas de minería, construcción y electricidad, enfocados en prevención de riesgos de alto riesgo e IPERC.
                    </p>
                </div>
                <a href="{{ route('cursos') }}" class="inline-flex items-center text-sm font-semibold text-blue-400 hover:text-blue-300 transition-colors">
                    Ver cursos de SST <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-slate-800/80 rounded-2xl p-8 border border-slate-700 hover:border-emerald-500/50 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mb-6">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Sistemas de Gestión de Calidad ISO</h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Formación especializada en interpretación, implementación y auditoría interna de normas internacionales ISO 9001, ISO 14001 e ISO 45001.
                    </p>
                </div>
                <a href="{{ route('cursos') }}" class="inline-flex items-center text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                    Ver normas ISO <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-slate-800/80 rounded-2xl p-8 border border-slate-700 hover:border-purple-500/50 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-3xl mb-6">
                        <i class="bi bi-building-check"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Soluciones Corporativas in-House</h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Planes de capacitación empresarial a medida para cumplimiento del Plan Anual de Capacitación en SST de su empresa con reporte de auditoría.
                    </p>
                </div>
                <a href="{{ route('paquetes') }}" class="inline-flex items-center text-sm font-semibold text-purple-400 hover:text-purple-300 transition-colors">
                    Servicios para empresas <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section> --}}

<!-- FAQ Accordion Component (Alpine.js) for Dwell Time & SEO Keyword Relevance -->
<section class="py-16 sm:py-20 lg:py-24 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                Resuelve tus dudas sobre {{ $enterprise->trade_name ?? 'nosotros' }}
            </h2>
            <p class="mt-2 text-gray-600">
                Información transparente sobre nuestra metodología, certificados e inscripciones.
            </p>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: null }">
            <!-- FAQ Item 1 -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button 
                    @click="activeFaq = (activeFaq === 1 ? null : 1)" 
                    class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 text-lg focus:outline-none hover:bg-gray-50 transition-colors">
                    <span class="flex items-center gap-3">
                        ¿Los certificados emitidos tienen validez curricular y laboral?
                    </span>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeFaq === 1 }"></i>
                </button>
                <div x-show="activeFaq === 1" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                    Sí, nuestros certificados cumplen con las exigencias del Ministerio de Trabajo (MINTRA), la Ley N° 29783 y normativas de fiscalización SUNAFIL. Cada certificado cuenta con un código único de verificación QR que permite autenticar su validez instantáneamente en nuestra plataforma.
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button 
                    @click="activeFaq = (activeFaq === 2 ? null : 2)" 
                    class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 text-lg focus:outline-none hover:bg-gray-50 transition-colors">
                    <span class="flex items-center gap-3">
                        ¿Cómo funciona la modalidad de estudio online?
                    </span>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeFaq === 2 }"></i>
                </button>
                <div x-show="activeFaq === 2" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                    Contamos con un aula virtual moderna disponible las 24 horas del día. Puedes estudiar a tu propio ritmo desde cualquier dispositivo (PC, laptop o smartphone), acceder a los video-módulos, descargar material en PDF y rendir tus evaluaciones online.
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button 
                    @click="activeFaq = (activeFaq === 3 ? null : 3)" 
                    class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 text-lg focus:outline-none hover:bg-gray-50 transition-colors">
                    <span class="flex items-center gap-3">
                        ¿Brindan capacitaciones para empresas e instituciones?
                    </span>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeFaq === 3 }"></i>
                </button>
                <div x-show="activeFaq === 3" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                    Por supuesto. Diseñamos e implementamos Programas Anuales de Capacitación en SST adaptados a los riesgos de tu sector (Minería, Construcción, Industria, Comercio, etc.), permitiéndote matricular y hacer seguimiento corporativo al desempeño de tus colaboradores.
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button 
                    @click="activeFaq = (activeFaq === 4 ? null : 4)" 
                    class="w-full text-left px-6 py-5 flex items-center justify-between font-bold text-gray-900 text-lg focus:outline-none hover:bg-gray-50 transition-colors">
                    <span class="flex items-center gap-3">
                        ¿Cómo puedo solicitar mayor información o soporte técnico?
                    </span>
                    <i class="bi bi-chevron-down text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeFaq === 4 }"></i>
                </button>
                <div x-show="activeFaq === 4" x-collapse class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                    Puedes contactarnos a través de nuestra sección de <a href="{{ url('contacto') }}" class="text-blue-600 underline font-semibold">Contacto</a>, escribirnos al WhatsApp corporativo o llamarnos al {{ $enterprise->phone_number_1 ?? 'nuestro teléfono oficial' }}. Nuestro equipo de asesores responderá tus inquietudes de inmediato.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final Call to Action -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-6 tracking-tight">
            ¿Listo para llevar tu carrera o empresa al siguiente nivel?
        </h2>
        <p class="text-lg sm:text-xl text-blue-100 mb-10 max-w-3xl mx-auto leading-relaxed">
            Únete a los miles de profesionales y empresas que confían en {{ $enterprise->trade_name ?? 'nosotros' }} para su certificación continua.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            @guest
            <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold text-lg px-8 py-4 rounded-xl shadow-lg hover:bg-blue-50 transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="bi bi-person-plus-fill"></i>
                Crear Cuenta Gratis
            </a>
            @endguest

            <a href="{{ route('cursos') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 text-white border border-blue-400/40 font-bold text-lg px-8 py-4 rounded-xl hover:bg-blue-500 transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="bi bi-journal-text"></i>
                Explorar Catálogo de Cursos
            </a>

            <a href="{{ url('contacto') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-transparent text-white border border-white/40 font-bold text-lg px-8 py-4 rounded-xl hover:bg-white/10 transition-all duration-300">
                <i class="bi bi-chat-dots-fill"></i>
                Contáctanos
            </a>
        </div>
    </div>
</section>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.8s ease-out 0.2s forwards;
    }
</style>
@endsection
