<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    {{ HTML::style('public/css/bootstrap.min.css') }}
    {{ HTML::style('public/css/bootstrap-responsive.min.css') }}
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
            
            {{ HTML::link('/', 'Bootstrap Application', array('class' => 'brand')) }}
            
            <div class="nav-collapse">
            
                <ul class="nav pull-right">
                    
                    <li>
                        @yield('return_link') 
                    </li>
                    
                </ul>
                
            </div> <!-- /nav-collapse -->

        </div> <!-- /container -->
        
    </div> <!-- /navbar-inner -->
    
</div> <!-- /navbar -->

<div id="content">
    
    <div class="container">
        
        <div class="row">
            
            <div class="span12">
                
                <h1 class="page-title">
                    <i class="icon-user"></i> @yield('title')              
                </h1>
                
                <div class="row">
                    
                    <div class="span12">
                
                        @yield('content')  
                        
                    </div> <!-- /span9 -->
                    
                </div> <!-- /row -->
                
            </div>

        </div>
        
    </div> <!-- /container -->
    
</div> <!-- /content -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{{ HTML::script('public/js/jquery-1.7.2.min.js') }}
{{ HTML::script('public/js/bootstrap.js') }}

  </body>
</html>