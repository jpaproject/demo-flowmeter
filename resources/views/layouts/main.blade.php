<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">

<head>
    @include('layouts.partials.head')
</head>


<!-- Top Bar Start -->

<body>
    <!-- Top Bar Start -->
    <div class="topbar d-print-none">
        @include('layouts.partials.navbar')
    </div>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <div class="startbar d-print-none">
        <!--start brand-->
        <div class="brand">
            {{-- <a href="index.html" class="logo">
                <span>
                    <img src="{{asset('assets/images/logos/general-logo.png')}}" alt="logo-small" class="logo-sm">
                </span>
            </a> --}}
        </div>
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu">
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                   @include('layouts.partials.sidebar')
                </div>
            </div>
            <!--end startbar-collapse-->
        </div>
        <!--end startbar-menu-->
    </div>
    <!--end startbar-->
    <div class="startbar-overlay d-print-none"></div>
    <!-- end leftbar-tab-menu-->


    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">

                @yield('content')
                <!--end row-->
            </div><!-- container -->

           @include('layouts.partials.footer')

            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->

   @include('layouts.partials.foot')

</body>
<!--end body-->

</html>
