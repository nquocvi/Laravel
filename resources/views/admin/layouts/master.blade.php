<!doctype html>
<html class="no-js" lang="">
<head>
    @include('admin.partials.head')
    @include('admin.partials.styles')
    @yield('css-style')
</head>

<body>
    @include('admin.partials.left_panel')

    <div id="right-panel" class="right-panel">

        @include('admin.partials.right_head')

        @yield('breadcrumbs')

        <div class="content">
            @yield('content')
        </div>

    </div>

    @include('admin.partials.right_footer')

    @include('admin.partials.scripts')
    @yield('js-script')  
</body>
</html>