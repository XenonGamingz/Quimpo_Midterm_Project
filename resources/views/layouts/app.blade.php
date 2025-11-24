<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library System</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 font-sans">
  <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
    <!-- Mobile topbar -->
    <header class="w-full bg-white shadow md:hidden">
      <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center gap-2">
          <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded flex items-center justify-center text-white font-bold">L</div>
            <div class="font-semibold">Library</div>
          </a>
        </div>
        <div>
          <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="text-sm text-red-600">Logout</button>
          </form>
        </div>
      </div>
    </header>
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow p-4 transform md:relative md:translate-x-0 transition-transform duration-200 ease-in-out">
      <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
          <div class="w-10 h-10 bg-indigo-600 rounded flex items-center justify-center text-white font-bold">L</div>
          <div>
            <div class="font-semibold">Library</div>
            <div class="text-xs text-gray-500">Management</div>
          </div>
        </a>
      </div>

      <nav>
        <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">Dashboard</a>
        <a href="{{ route('library.categories.index') }}" class="block mt-1 py-2 px-3 rounded {{ request()->routeIs('library.categories.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">Categories</a>
      </nav>

      <div class="mt-8 border-t pt-4">
        <div class="text-sm text-gray-600">{{ auth()->user()->name ?? 'User' }}</div>
        <form method="POST" action="{{ route('logout') }}">@csrf
          <button class="mt-2 text-sm text-red-600">Logout</button>
        </form>
      </div>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6">
      @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
      @endif
      @yield('content')
    </main>
  </div>
</body>
</html>
