<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ $title ?? 'Hello Shop' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}" />
    {{-- @vite('resources/js/app.js')
    @vite('resources/sass/app.scss') --}}

    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>

    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/toastify.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/toastify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/progress.css') }}">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

</head>

<body>
    <div id="loader" class="LoadingOverlay d-none">
        <div class="Line-Progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    @include('components.layouts.nav')
    @include('components.layouts.sidebar')
    <script>
        function MenuBarClickHandler() {
            let sideNav = document.getElementById('sideNavRef');
            let content = document.getElementById('contentRef');
            if (sideNav.classList.contains("side-nav-open")) {
                sideNav.classList.add("side-nav-close");
                sideNav.classList.remove("side-nav-open");
                content.classList.add("content-expand");
                content.classList.remove("content");
            } else {
                sideNav.classList.remove("side-nav-close");
                sideNav.classList.add("side-nav-open");
                content.classList.remove("content-expand");
                content.classList.add("content");
            }
        }
    </script>
    <div id="contentRef" class="content">
        @yield('content')
    </div>
</body>

{{-- <script>
    (async () => {
        let res = await axios.get("{{ route('shop.info') }}")
        if (res.status === 200 && res.data['status'] === 'success') {
            let data = res.data['data'];
            document.title = data['title'];
            // let logoUrl = '{{ url('uploads/logo') }}' + '/' + data['logo'];
            // $('.nav-logo').attr('src', logoUrl);
        }
    })();
</script> --}}

</html>
