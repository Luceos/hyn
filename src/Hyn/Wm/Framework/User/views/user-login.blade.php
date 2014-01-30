@extends('hynwmanage::index')


@section('footer-js')
<script type='text/javascript' src='/resources/theme/avant/assets/js/jquery-1.10.2.min.js'></script> 
<script>

	$(function()
	{
		$("[data-submit-form]").on("click",function(e)
		{
			e.preventDefault();
			$("form").submit();
		});
	});
</script>
@stop

@section('outer-header')
@stop

@section('headerbar')
@stop

@section('page-container')
<div class="verticalcenter">
	<a href="{{ URL::route("manage") }}">
		{{--<img src="assets/img/logo-big.png" alt="Logo" class="brand" />--}}
	</a>
	<div class="panel panel-primary">
		<div class="panel-body">
			<h4 class="text-center" style="margin-bottom: 25px;">{{ _("Log in to get started") }} {{_("or") }} <a href="{{ URL::route("signup") }}">{{ _("Sign Up") }}</a></h4>
				@if (Session::get("loginMessage"))
				<div class="alert">
					{{ Session::get("loginMessage") }}
				</div>
				@endif
				{{ Form::open( array( "class" => "form-horizontal" ) ) }}
					{{ Form::hidden( "user-login" , 1 ) }}
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								{{ Form::text( "username" , NULL, array("class" => "form-control" )) }}
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								{{ Form::password( "password" , array("class" => "form-control" )) }}
							</div>
						</div>
					</div>
					<div class="clearfix">
						<div class="pull-right"><label><input type="checkbox" style="margin-bottom: 20px" checked=""> Remember Me</label></div>
					</div>
				{{ Form::close() }}
					
		</div>
		<div class="panel-footer">
			<a href="{{ URL::route("forgotpassword") }}" class="pull-left btn btn-link" style="padding-left:0">{{ _("Forgot password?") }}</a>
			
			<div class="pull-right">
				<button href="#" class="btn btn-default">{{ _("Reset") }}</button>
				<button data-submit-form class="btn btn-primary">{{ _("Log In") }}</button>
			</div>
		</div>
	</div>
 </div>
@stop