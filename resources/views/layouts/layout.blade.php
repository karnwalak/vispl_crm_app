<?php if (Auth::check()) {
    $usertype = Auth::user()->usertype;
    $userid = Auth::user()->id;
    if ($usertype == 4) {
        ?> <script> window.location.href = "{{ SITEURL }}";</script> <?php exit;
    }
}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SmartPing') }}</title>
        <!-- Scripts -->
        <link rel="apple-touch-icon" href="{{ asset('public/images/smartping.ico') }} ">
        <link rel="shortcut icon" sizes="16x16" href="{{ asset('public/images/smartping.ico') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/images/smartping.ico') }}">
        <!-- Custom fonts for this template-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet">
        <!-- Custom styles for this page -->
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('public/build/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
        <!-- END Page Level CSS-->
        <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            @include('include.header')

            @include('include.sidebar')

            @yield('content')

            @include('include.footer')
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- Moment JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('public/js/custom.js') }}"></script>
    <script src="{{ asset('public/js/datatable.js') }}"></script>
    <!-- Include Bootstrap DateTimePicker CDN -->
    <script src="{{ asset('public/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    {{-- <link
        href=
            "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">

      <script src=
            "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
      </script> --}}
    <script>
    jQuery(document).ready(function ($) {
        if (window.jQuery().datetimepicker) {
            $('.datetimepicker').datetimepicker({
                // Formats
                // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
                format: 'DD/MM/YYYY hh:mm A',
                sideBySide: true,
                showTodayButton: true,
                showClear: true,
                defaultDate: moment(),
                // Your Icons
                // as Bootstrap 4 is not using Glyphicons anymore
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });

            $('.datepicker').datetimepicker({
                // Formats
                // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
                format: 'YYYY-MM-DD',
                sideBySide: true,
                showTodayButton: true,
                showClear: true,
                // Your Icons
                // as Bootstrap 4 is not using Glyphicons anymore
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });
        }
    });
    $(document).ready(function () {
        $(".expand-data").click(function () {
            var $this = $(this);
            $("#show-data").slideToggle()

            $this.toggleClass("expanded");

            if ($this.hasClass("expanded")) {
                $this.html("<i class='fa fa-minus' aria-hidden='true'></i>");
            } else {
                $this.html("<i class='fa fa-plus' aria-hidden='true'></i>");
            }
        });
    });
    </script>
</body>
</html>
