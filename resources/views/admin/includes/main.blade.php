@include('admin.includes.header')
<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Content Wrapper -->
    @include('admin.includes.sidebar')

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            @include('admin.includes.navbar')
                @yield('content')
            </div>

</div>
@include('admin.includes.footer')
