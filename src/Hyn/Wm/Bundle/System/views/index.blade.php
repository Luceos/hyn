<html>
	<head>
		<title>{{ $title or _("view") }} - {{ $domain or "Hyn WM" }}</title>
		{{ \HTML::style('/resources/theme/avant/assets/css/styles.min.css') }}
		{{ \HTML::style('//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600') }}
		{{ \HTML::style('/resources/theme/avant/assets/plugins/progress-skylo/skylo.css') }}
		{{ \HTML::style('/resources/theme/avant/assets/js/jqueryui.css') }}
		{{ \HTML::style('/resources/theme/avant/assets/plugins/codeprettifier/prettify.css') }}
		{{ \HTML::style('/resources/theme/avant/assets/plugins/form-toggle/toggles.css') }} 
	</head>
	
	<body class="{{ $bodyclass or "" }}">
		@section('headerbar')
		<div id="headerbar">
			<div class="container">
				<div class="row">
					<div class="col-xs-6 col-sm-2">
					<a href="#" class="shortcut-tiles tiles-brown">
						<div class="tiles-body">
							<div class="pull-left"><i class="fa fa-pencil"></i></div>
						</div>
						<div class="tiles-footer">
							Create Post
						</div>
					</a>
					</div>
					<div class="col-xs-6 col-sm-2">
					<a href="#" class="shortcut-tiles tiles-grape">
						<div class="tiles-body">
							<div class="pull-left"><i class="fa fa-group"></i></div>
							<div class="pull-right"><span class="badge">2</span></div>
						</div>
						<div class="tiles-footer">
							Contacts
						</div>
					</a>
					</div>
					<div class="col-xs-6 col-sm-2">
					<a href="#" class="shortcut-tiles tiles-primary">
						<div class="tiles-body">
							<div class="pull-left"><i class="fa fa-envelope-o"></i></div>
							<div class="pull-right"><span class="badge">10</span></div>
						</div>
						<div class="tiles-footer">
							Messages
						</div>
					</a>
					</div>
					<div class="col-xs-6 col-sm-2">
					<a href="#" class="shortcut-tiles tiles-inverse">
						<div class="tiles-body">
							<div class="pull-left"><i class="fa fa-camera"></i></div>
							<div class="pull-right"><span class="badge">3</span></div>
						</div>
						<div class="tiles-footer">
							Gallery
						</div>
					</a>
					</div>

					<div class="col-xs-6 col-sm-2">
					<a href="#" class="shortcut-tiles tiles-midnightblue">
						<div class="tiles-body">
							<div class="pull-left"><i class="fa fa-cog"></i></div>
						</div>
						<div class="tiles-footer">
							Settings
						</div>
					</a>
					</div>
					<div class="col-xs-6 col-sm-2">
					<a href="#" class="shortcut-tiles tiles-orange">
						<div class="tiles-body">
							<div class="pull-left"><i class="fa fa-wrench"></i></div>
						</div>
						<div class="tiles-footer">
							Plugins
						</div>
					</a>
					</div>
						
				</div>
			</div>
		</div>
		@show
		@section('outer-header')
		<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
			@section('header')
			<a id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar"></a>
			<a id="rightmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="left" title="Toggle Infobar"></a>

			<div class="navbar-header pull-left">
				<a class="navbar-brand" href="{{ URL::route("manage") }}">Hyn WM</a>
			</div>
			@if (Auth::check())
			<ul class="nav navbar-nav pull-right toolbar">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle username" data-toggle="dropdown">
						<span class="hidden-xs">
							{{ Auth::user() -> username }} <i class="fa fa-caret-down"></i>
						</span>
						<img src="//www.gravatar.com/avatar/{{ md5(Auth::user() -> email) }}.png?s=24" alt="Dangerfield" />
					</a>
					<ul class="dropdown-menu userinfo arrow">
						<li class="username">
							<a href="{{ URL::route("manage:user",Auth::user()->id) }}">
								<div class="pull-left"><img class="userimg" src="//www.gravatar.com/avatar/{{ md5(Auth::user() -> email) }}.png?s=30" alt="Jeff Dangerfield"/></div>
								<div class="pull-right">
									<h5>{{ Auth::user() -> username }}</h5>
									<small>@lang("hynwmanage::manage.logged_in_as") 
										<span>{{ Auth::user() -> username }}</span>
									</small>
								</div>
							</a>
						</li>
						<li class="userlinks">
							<ul class="dropdown-menu">
								<li><a href="#">Edit Profile <i class="pull-right fa fa-pencil"></i></a></li>
								<li><a href="#">Account <i class="pull-right fa fa-cog"></i></a></li>
								<li><a href="#">Help <i class="pull-right fa fa-question-circle"></i></a></li>
								<li class="divider"></li>
								<li><a href="#" class="text-right">Sign Out</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="@if (isset($messages))hasnotifications @endif dropdown-toggle" data-toggle='dropdown'><i class="fa fa-envelope"></i>@if (isset($messages))<span class="badge">{{ isset($messages) ? count($messages) : 0 }}</span>@endif</a>
					<ul class="dropdown-menu messages arrow">
						<li class="dd-header">
							<span>You have {{ isset($messages) ? count($messages) : 0 }}} new message(s)</span>
							<span><a href="#">Mark all Read</a></span>
						</li>
						<div class="scrollthis">
							@section('messages-list')
								{{--
								<li><a href="#" class="active">
									<span class="time">6 mins</span>
									<img src="assets/demo/avatar/doyle.png" alt="avatar" />
									<div><span class="name">Alan Doyle</span><span class="msg">Please mail me the files by tonight.</span></div>
								</a></li>
								--}}
							@show
						</div>
						<li class="dd-footer"><a href="#">View All Messages</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="@if (isset($notifications))hasnotifications @endifdropdown-toggle" data-toggle='dropdown'><i class="fa fa-bell"></i>@if (isset($notifications))<span class="badge">{{ isset($notifications) ? count($notifications) : 0 }}</span>@endif</a>
					<ul class="dropdown-menu notifications arrow">
						<li class="dd-header">
							<span>You have {{ isset($notifications) ? count($notifications) : 0 }} new notification(s)</span>
							<span><a href="#">Mark all Seen</a></span>
						</li>
						<div class="scrollthis">
							@section('notifications-list')
								{{-- 
								<li>
									<a href="#" class="notification-user active">
										<span class="time">4 mins</span>
										<i class="fa fa-user"></i>
										<span class="msg">New user Registered. </span>
									</a>
								</li>
								--}}
							@show
						</div>
						<li class="dd-footer"><a href="#">View All Notifications</a></li>
					</ul>
				</li>
				<li>
						<a href="#" id="headerbardropdown"><span><i class="fa fa-level-down"></i></span></a>
				</li>
			</ul>
			@endif
			@show
		</header>
		@show
		
		@section('page-container')
		<div id="page-container">
			@section('left-navigation')
			<nav id="page-leftbar" role="navigation">
				<ul class="acc-menu" id="sidebar">
					@section('left-nav-search')
					<li id="search">
						<a href="javascript:;"><i class="fa fa-search opacity-control"></i></a>
						<form>
						<input type="text" class="search-query" placeholder="Search...">
						<button type="submit"><i class="fa fa-search"></i></button>
						</form>
					</li>
					<li class="divider"></li>
					@show
					<li @if (Route::currentRouteName() == "manage") class="active"@endif><a href="{{ URL::route("manage") }}"><i class="fa fa-home"></i> <span>@lang("hynwmanage::manage.dashboard") </span></a></li>
					<li @if (Route::currentRouteName() == "manage:system") class="active"@endif><a href="{{ URL::route("manage:system") }}"><i class="fa fa-sitemap"></i> <span>@lang("hynwmanage::manage.system")</span></a></li>
					<li @if (Route::currentRouteName() == "manage:websites") class="active"@endif><a href="{{ URL::route("manage:websites") }}"><i class="fa fa-globe"></i> <span>@choice("hynwmanage::manage.website",2)</span></a></li>
					<li @if (Route::currentRouteName() == "manage:users") class="active"@endif><a href="{{ URL::route("manage:users") }}"><i class="fa fa-users"></i> <span>@choice("hynwmanage::manage.system_user",2)</span></a></li>
				</ul>
			</nav>
			@show
			
			<div id="page-content">
				<div id="wrap">
					{{ $content or "" }}
				</div>
			</div>
			
			@section('footer')
			<footer role="contentinfo">
				<div class="clearfix">
					<ul class="list-unstyled list-inline pull-left">
						<li><a href="//hyn.me">Hyn WM &copy; {{ date("Y") }}</a></li>
					</ul>
				<button class="pull-right btn btn-inverse-alt btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
				</div>
			</footer>
			@show
		</div>
		@show
		@section('footer-js')
		{{ \HTML::script('/resources/theme/avant/assets/js/jquery-1.10.2.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/js/jqueryui-1.10.3.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/js/bootstrap.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/js/enquire.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/js/jquery.cookie.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/js/jquery.nicescroll.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/plugins/codeprettifier/prettify.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/plugins/easypiechart/jquery.easypiechart.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/plugins/sparklines/jquery.sparklines.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/plugins/form-toggle/toggle.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/plugins/knob/jquery.knob.min.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/plugins/progress-skylo/skylo.js') }}
		{{ \HTML::script('/resources/theme/avant/assets/js/application.js') }}

		<script>
			$(function()
			{
				$(".easypiechart").easyPieChart();
				$("[data-tooltip]").tooltip({
					container:	"body",
					title:		function()
					{
						return $(this).attr("data-tooltip");
					}
				});
			});
		</script>
		@show
	</body>
</html>
