@include('partials.header')

<body>
    @include('components.navbar')

    @yield('content')

    {{-- @include('partials.footer') --}}

    @stack('scripts')
</body>
