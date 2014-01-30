
					<div id="page-heading">
						<ol class="breadcrumb">
							<li><a href="{{ URL::route("manage") }}">@lang("hynwmanage::manage.dashboard")</a></li>
							<li class="active"><a href="{{ URL::route("manage:users") }}">{{ _("System users") }}</a></li>
						</ol>

						<h1>{{ _("System users") }}</h1>
					</div>


					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								<div class="panel panel-primary">
									<div class="panel-body">
										<table class="table table-striped table-hover">
											<thead>
												<tr>
													<th>
														&nbsp;
													</th>
													<th>
														<i class="fa fa-user"></i> {{ _("Username") }}
													</th>
													<th>
														<i class="fa fa-envelope"></i> {{ _("E-mail address") }}
													</th>
													<th><i class="fa fa-bars"></i></th>
												</tr>
											</thead>
											<tbody>
												@foreach ($users as $user)
												<tr>
													<td>
														@if ($user->systemAdmin)
														<i class="fa fa-star"></i>
														@endif
													</td>
													<td>
														<a href="{{ URL::route("manage:user",$user->id) }}">{{ $user->username }}</a>
													</td>
													<td>
														<a href="{{ URL::route("manage:user",$user->id) }}">{{ $user->email }}</a>
													</td>
													<td>
													
													</td>
												</tr>
												@endforeach
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3">
														{{ $users->links() }}
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
{{--									<div class="panel-footer">
										@if ($errors)
											@foreach ($errors as $error)
											<div class="alert alert-dismissable alert-danger">
												{{ $error }}
											</div>
											@endforeach
										@endif
										{{ Form::open( array( "class" => "form-horizontal" ) ) }}
											<div class="form-group">
												{{ Form::label("username" , _("Username"), array( "class" => "col-sm-2 control-label" )) }}
												<div class="col-sm-10">
													{{ Form::text( "username" , NULL, array( "class" => "form-control", "placeholder" => _("uniqueusername") ) ) }}
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-2 col-sm-10">
													{{ Form::submit(_("add system user"), array("class" => "btn btn-primary")) }}
												</div>
											</div>
										{{ Form::close() }}
									</div>
--}}
								</div>
							</div>
						</div>
					</div> 
 
