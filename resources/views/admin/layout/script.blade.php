  <!-- JAVASCRIPT -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{asset('backend')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('backend')}}/libs/simplebar/simplebar.min.js"></script>
  <script src="{{asset('backend')}}/libs/node-waves/waves.min.js"></script>
  <script src="{{asset('backend')}}/libs/feather-icons/feather.min.js"></script>
  <script src="{{asset('backend')}}/js/pages/plugins/lord-icon-2.1.0.js"></script>
  <script src="{{asset('backend')}}/js/plugins.js"></script>

  <!-- apexcharts -->
  <script src="{{asset('backend')}}/libs/apexcharts/apexcharts.min.js"></script>

  <!-- Vector map-->
  <script src="{{asset('backend')}}/libs/jsvectormap/js/jsvectormap.min.js"></script>
  <script src="{{asset('backend')}}/libs/jsvectormap/maps/world-merc.js"></script>

  <!--Swiper slider js-->
  <script src="{{asset('backend')}}/libs/swiper/swiper-bundle.min.js"></script> 
  <!-- Dashboard init -->
  <script src="{{asset('backend')}}/js/pages/dashboard-ecommerce.init.js"></script>

  <!-- ckeditor -->
  <script src="{{asset('backend')}}/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

  <!-- quill js -->
  <script src="{{asset('backend')}}/libs/quill/quill.min.js"></script>

  <!-- init js -->
  <script src="{{asset('backend')}}/js/pages/form-editor.init.js"></script>
  <!--datatable js-->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  {!! NoCaptcha::renderJs() !!}
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="{{asset('backend')}}/js/pages/datatables.init.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <!-- App js -->
  <script src="{{asset('backend')}}/js/app.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    $(document).ready(function() {
        $('#dataTbl').DataTable();
    });

    function flashMessage(type="success",message=""){
        if(document.getElementById("alert-"+type)){
            document.getElementById("alert-"+type).classList.remove('d-none');
            document.getElementById("alert-"+type+"-message").innerHTML = message;
            setTimeout(function() {
                document.getElementById("alert-" + type).classList.add('d-none');
            }, 2000);
        }
    }

  </script>

  @yield('script')