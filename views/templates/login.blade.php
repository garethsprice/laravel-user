<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    {{ HTML::style('public/css/bootstrap.min.css') }}
    {{ HTML::style('public/css/bootstrap-responsive.min.css') }}
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    {{ HTML::style('public/css/font-awesome.css') }}

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
  </head>

<body>
    
<div class="navbar navbar-fixed-top">
    
    <div class="navbar-inner">
        
        <div class="container">
            
            <a class="brand" href="/">Bootstrap Application</a>
            
        </div> <!-- /container -->
        
    </div> <!-- /navbar-inner -->
    
</div> <!-- /navbar -->


<div id="login-container">
    
    <div id="login-header">
        
        <h3>@yield('title')</h3>
        
    </div> <!-- /login-header -->
    
    <div id="login-content" class="clearfix">

    @yield('content')
            
    </div> <!-- /login-content -->
    
</div> <!-- /login-wrapper -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="./js/jquery-1.7.2.min.js"></script>

<script src="./js/bootstrap.js"></script>

  </body>
</html>