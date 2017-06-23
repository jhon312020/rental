<?php
//print_r($setting);die;
?>
<head>
    <meta charset="UTF-8">
    <title> Rent - @yield('htmlheader_title', $setting['title']) </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset('/css/skins/skin-blue.css') }}" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />

	<!-- Datatable css -->
    <link href="{{ asset('/plugins/boostrap-datatable/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/plugins/boostrap-datatable/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('/css/style.css') }}" rel="stylesheet" type="text/css" />
	<!-- Jquery alert css-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.0/jquery-confirm.min.css">

    <!-- File input css-->
    <link rel="stylesheet" href="{{ asset('/plugins/fileinput/css/fileinput.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('/plugins/fileinput/css/theme.css') }}" rel="stylesheet" type="text/css" />

    <!-- Datepicker css -->
    <link href="{{ asset('/plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css" />

    <!-- file-browse css -->
    <link href="{{ asset('/plugins/modenizer/css/file-browse.css') }}" rel="stylesheet" type="text/css" />

    <!-- Select2 css -->
    <link href="{{ asset('/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Select2 css -->
    <link href="{{ asset('/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Autocomplete css -->
    <link href="{{ asset('/plugins/autocomplete/css/autocomplete.css') }}" rel="stylesheet" type="text/css" />

    <!-- Autocomplete css -->
    <link href="{{ asset('/css/grid.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

    <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('/plugins/iCheck/all.css') }}">

  <!-- Toast -->
  <link rel="stylesheet" href="{{ asset('/plugins/toast/css/jquery.toast.css') }}">

  <!-- Date range picker css -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

  <!-- Dashboard css -->
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

  <!-- Animation css -->
  <link rel="stylesheet" href="{{ asset('plugins/animation/css/animate.min.css') }}">

  <!-- google loader css -->
  <link rel="stylesheet" href="{{ asset('css/loader.css') }}">

  <!-- Materialize css -->
  <!--<link rel="stylesheet" href="{{ asset('plugins/materialize/css/materialize.css') }}">-->
  <!-- jQuery 2.1.4 -->
    <script src="{{ asset('/plugins/jQuery/jquery-1.12.4.js') }}"></script>

    <!-- Progressbar js -->
    <script src="{{ asset('plugins/progressbar/js/progressbar.js') }}" type="text/javascript"></script>
    
    <script>
    var ASSETS_PATH = "{{ asset('img') }}";
    </script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
      });
      var active_menu = {!! isset($active_menu) ? json_encode($active_menu) : json_encode([]) !!};
      var APP_URL = {!! json_encode(url('/')) !!};
      var is_admin = {!! isset($roles) && $roles->role_name == 'admin' ? 1 : 0 !!};
      var report_start_date = "{{ date('d/m/Y') }}";
      var report_end_date = "{{ date('d/m/Y') }}";
      if (!is_admin) {
      	report_start_date = "{{ date('d/m/Y', strtotime('-2 days')) }}";
      	report_end_date = "{{ date('d/m/Y') }}";
      }
      var ajax_url = { 
              rent_serach : "{{action('GuestsController@getGuestByKey')}}",
              bill_create : "{{action('AjaxController@createNewBill')}}",
              rent_create : "{{action('AjaxController@createNewRentIncome')}}",
              get_guest_rent : "{{action('AjaxController@getGuestDetailsForRoom')}}",
              update_electric_bill : "{{action('AjaxController@updateElectricityBillByKey')}}",
              remove_electric_bill : "{{action('AjaxController@deleteBillsByIds')}}",
              update_rent_income : "{{action('AjaxController@updateRoomRentByKey')}}",
              remove_rent_income : "{{action('AjaxController@deleteRentIncomesByIds')}}",
              rent_update : "{{action('AjaxController@updateRent')}}",
              move_to_active_electric_bill : "{{action('AjaxController@moveToActiveElectricBills')}}", 
              move_to_active_rent : "{{action('AjaxController@moveToActiveRents')}}",
              new_rent_by_room_id :  "{{action('AjaxController@addNewRentByRoom')}}",
              get_room_report :  "{{action('AjaxController@roomReport')}}",
              get_rent_report :  "{{action('AjaxController@rentReport')}}",
              get_income_report :  "{{action('AjaxController@incomeReport')}}",
              get_income_report_month :  "{{action('AjaxController@incomeReportMonth')}}",
              get_income_report_year :  "{{action('AjaxController@incomeReportYear')}}",
              get_income_report_between_date : "{{action('AjaxController@incomeReportBetweenDate')}}",
              get_expense_report_month :  "{{action('AjaxController@expenseReportMonth')}}",
              get_expense_report_year :  "{{action('AjaxController@expenseReportYear')}}",
              get_expense_report_between_date : "{{action('AjaxController@expenseReportBetweenDate')}}",
              get_electricity_bill_report_month :  "{{action('AjaxController@getElectricityBillReportMonth')}}",
              get_electricity_bill_report_year : "{{action('AjaxController@getElectricityBillReportYear')}}",
              get_electricity_bill_report_between_month : "{{action('AjaxController@getElectricityBillReportBetweenMonth')}}",
              get_guest_details_by_type : "{{action('AjaxController@getGuestDetailsByType')}}",
              get_guest_by_id : "{{action('AjaxController@getGuestById')}}",
              add_new_rent_by_room_guest : "{{action('AjaxController@addNewRentByRoomAndGuest')}}",
              get_bill_by_room_no : "{{action('AjaxController@getBillByRoomNo')}}",
              get_rent_by_room_no : "{{action('AjaxController@getRentByRoomNo')}}",
              create_income_type : "{{action('AjaxController@createIncomeType')}}",
              create_expense_type : "{{ action('AjaxController@createExpenseType')}}",
              create_new_income : "{{ action('AjaxController@createNewIncome') }}",
              get_last_transactions: "{{ action('AjaxController@getLast5PaidRental') }}",
              remove_rents: "{{ action('AjaxController@removeRents') }}",
              get_guests: "{{ action('AjaxController@getGuests') }}",
              get_guest_income_report_between_date : "{{action('AjaxController@getGuestIncomeBetweenDate')}}",
              get_guest_details : "{{action('AjaxController@getGuestDetails', 'guest_id')}}",
              update_settlement : "{{action('AjaxController@updateSettlement')}}",
              get_settlement : "{{action('AjaxController@calculateSettlement')}}",
              update_electricity_rent : "{{action('AjaxController@updateElectricityBillToRent')}}",
              get_settle_rent : "{{action('AjaxController@getSettledGuestDetailsForRoom')}}",
          };
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
