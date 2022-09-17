<!DOCTYPE html>
<?php  if (!Auth::user()) {?>
	<script> window.location.href="{{ SITEURL }}login" </script>
   <?php exit; } ?>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
<link rel="apple-touch-icon" href="{{ SITEURL }}public/images/newlogo.png">
  <link rel="shortcut icon" type="image/x-icon" href="{{ SITEURL }}public/images/newlogo.png>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
 
 
<title>Mera App : Service Management</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="author" content="meraapp.com">
<meta name="robots" content="all">
<link rel="canonical" href="https://www.merapp.com/" /> 

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
        <!-- Fonts -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
<!-- Global site tag (gtag.js) - Google Analytics -->
 
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
 
 
</head>
<body> <!-- 5aa2dc 3c92ee  -->
	<section>
@include('inc.header')

@yield('content')

@include('inc.footer') 
	</section>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
 
<script>

</script>

 
</body>
</html>
