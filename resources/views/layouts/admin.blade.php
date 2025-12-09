<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/photos/ipf-logo.png') }}">
    <title>@yield('title') - Admin</title>
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.all.min.css') }}">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        API_URL = "{{ url('/') }}";
    </script>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h1 class="text-xl font-bold">Panel Admin</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700' : '' }}">
                    Categorías
                </a>
                <a href="{{ route('admin.courses.index') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.courses.*') ? 'bg-blue-700' : '' }}">
                    Cursos
                </a>
                <a href="{{ route('admin.documents.index') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.documents.*') ? 'bg-blue-700' : '' }}">
                    Documentos
                </a>
                <a href="{{ route('admin.exams.index') }}" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.exams.*') ? 'bg-blue-700' : '' }}">
                    Exámenes
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">@yield('title')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->names }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
