@include('admin.includes.header')
<!-- Page Wrapper -->
<div id="wrapper">
    @include('admin.includes.sidebar')
    @yield('content')
</div>
@include('admin.includes.footer')
