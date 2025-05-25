<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
@include('admin.layout.head')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        padding-left: 0px!important;
        padding-right: 0px!imoportant;
    }
</style>
<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

       @include('admin.layout.header')

        <!-- removeNotificationModal -->
        <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- ========== App Menu ========== -->
        @include('admin.layout.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div style="margin: 0px 10px;">
                <div style="padding-top: 70px; "></div>
                @include('admin.layout.flash-message')
            </div>
            @yield('content')
            <!-- End Page-content -->

           @include('admin.layout.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


  @include('admin.layout.script')

</body>
</html>