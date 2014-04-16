
					<div id="page-heading">
						<ol class="breadcrumb">
							<li><a href="{{ URL::route("manage") }}">@lang("hynwmanage::manage.dashboard")</a></li>
							<li class="active"><a href="{{ URL::route("manage:websites") }}">@choice("hynwmanage::manage.website",2)</a></li>
						</ol>

						<h1>@choice("hynwmanage::manage.website",2)</h1>
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
														<i class="fa fa-globe"></i> {{ _("Primary domain") }}
													</th>
													<th class="text-center">
														<i class="fa fa-globe"></i> {{ _("Domains") }}
													</th>
													<th class="text-center">
														<i class="fa fa-hdd-o"></i> {{ _("Disk usage in") }} MB
													</th>
													<th class="text-center">
														{{ _("E-mailadresses") }}
													</th>
													<th class="text-center"><i class="fa fa-bars"></i></th>
												</tr>
											</thead>
											<tbody>
												@foreach ($websites as $website)
												<tr>
													<td>
														<a href="{{ URL::route("manage:website",$website->id) }}">{{ $website->primary->hostname }}</a>
													</td>
													<td class="text-center">
														<a href="{{ URL::route("manage:website",$website->id) }}">{{ count($website->domains) }}</a>
													</td>
													<td class="text-center">
														{{ $website->diskspaceUsed("MB") }}
													</td>
													<td class="text-center">
														-
													</td>
													<td class="text-center">
														
													</td>
												</tr>
												@endforeach
											</tbody>
											<tfoot>
												<tr>
													<td colspan="5">
														{{ $websites->links() }}
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
									@if ($admin)
									<div class="panel-footer">
										@if ($errors)
											@foreach ($errors as $error)
											<div class="alert alert-dismissable alert-danger">
												{{ $error }}
											</div>
											@endforeach
										@endif
										{{ Form::open( array( "class" => "form-horizontal" ) ) }}
											<div class="form-group">
												{{ Form::label("hostname" , _("primary hostname"), array( "class" => "col-sm-2 control-label" )) }}
												<div class="col-sm-10">
													{{ Form::text( "hostname" , NULL, array( "class" => "form-control", "placeholder" => _("domain.com") ) ) }}
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-2 col-sm-10">
													{{ Form::submit(_("create"), array("class" => "btn btn-primary")) }}
												</div>
											</div>
										{{ Form::close() }}
									</div>
									@endif
								</div>
							</div>
						</div>
					</div> 
