<?php if (Auth::check()) {  $usertype = Auth::user()->usertype;$userid = Auth::user()->id; 
if($usertype == 2 || $usertype == 3  ) { ?> <script> window.location.href="{{ SITEURL }}home"; </script> <?php  exit; } }
  ?>
<?php $producturl = $_SERVER['REQUEST_URI'];
  $newurl = strtok($producturl, '?');
if (isset($newurl))
{
$newurl1 = strtok($newurl, '/');
$newurl2 = substr($newurl, strrpos($newurl, '/') + 1);
}
?>
<!DOCTYPE html>
<?php /* if (Auth::check()) {  $usertype = Auth::user()->usertype;$userid = Auth::user()->id; 
 if($usertype =='' || $usertype ==0 ) { ?> <script> window.location.href="{{ SITEURL }}home"; </script> <?php  exit; } 
} */ ?>
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
<?php /*?><!-- Fonts -->
<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,600italic,700,700italic,800' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'><?php */?>
<!-- Global site tag (gtag.js) - Google Analytics -->
 
</head>
<body >



@yield('content')

 

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
 
<script>

</script>
 

</body>
</html>
