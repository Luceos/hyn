
<div id="page-heading">
	<ol class="breadcrumb">
		<li><a href="{{ URL::route("manage") }}">@lang("hynwmanage::manage.dashboard")</a></li>
		<li><a href="{{ URL::route("manage:websites") }}">@choice("hynwmanage::manage.website",2)</a></li>
		<li class='active'><a href="{{ URL::route("manage:website",$website->id) }}">{{ $website->primary->hostname }}</a>
	</ol>

	<h1>{{ $website->primary -> hostname }}</h1>
</div>


<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4><i class="fa fa-info icon-highlight"></i> @lang("hynwmanage::manage.general_info")</h4>
					<div class="options">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#stats" data-toggle="tab">@lang("hynwmanage::manage.statistics")</a></li>
							<li><a href="#paths" data-toggle="tab">@choice("hynwmanage::manage.path",2)</a></li>
							<li><a href="#tables" data-toggle="tab">@choice("hynwmanage::manage.table",2)</a></li>
							<li><a href="#limits" data-toggle="tab">@choice("hynwmanage::manage.limit",2)</a></li>
						</ul>
					</div>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<div id="stats" class="tab-pane active row">
							<div class="col-xs-6 col-sm-6 col-md-4">
								@if ($website->diskspacePercentage())
								<div class="easypiechart" id="diskusage" data-percent="{{ $website->diskspacePercentage() }}" data-size="90">
									<span class="percent">{{ $website->diskspacePercentage() }}</span>
								</div>
								<label for="diskusage">@lang("hynwmanage::manage.disk_usage")</label>
								<hr class="visible-sm visible-xs">
								@else
								<div class="alert alert-warning">
									@lang("hynwmanage::manage.warn_no_disk_usage_limit"): 
									@if ($website->diskspaceUsed("MB"))
										{{ $website->diskspaceUsed("MB") }} MB
									@else
										-
									@endif
									@lang("hynwmanage::manage.used")
								</div>
								@endif
								@if ($website->databaseSize)
								<div class="alert alert-warning">
									@lang("hynwmanage::manage.warn_no_database_usage_limit"): 
									{{ $website->databaseSize }} B
									@lang("hynwmanage::manage.used") 
								</div>
								@endif
							</div>
						</div>
						<table class="table table-striped table-hover table-condensed tab-pane" id="paths">
							<tr>
								<th>
									@lang("hynwmanage::manage.website_id")
								</th>
								<td>
									{{ $website -> websiteID }}
								</td>
							</tr>
							<tr>
								<th>
									@lang("hynwmanage::manage.path_base")
								</th>
								<td>
									{{ $website -> path }}
								</td>
							</tr>
							<tr>
								<th>
									@lang("hynwmanage::manage.path_view")
								</th>
								<td>
									{{ $website -> pathViews }}
								</td>
							</tr>
							<tr>
								<th>
									@lang("hynwmanage::manage.path_media")
								</th>
								<td>
									{{ $website -> pathMedia }}
								</td>
							</tr>
						</table>
						<table class="table table-striped table-hover table-condensed tab-pane" id="tables">
							@foreach ($website -> requiredTables as $table )
							<tr>
								<th>
									{{ $table }}
								</th>
								<td>
									@if (\Schema::connection("website-modify") -> hasTable($table))
									<i class="fa fa-check"></i>
									@else
									<i class="fa fa-exclamation"></i>
									@endif
								</td>
							</tr>
							@endforeach
						</table>
						<table class="table table-striped table-hover table-condensed tab-pane" id="limits">
							@foreach ($website -> limits as $limit)
							<tr>
								<th>
									{{ $limit -> type }}
								</th>
								<td>
									{{ $limit -> value }} {{ $limit -> unit }}
								</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
				@if ($admin)
				<div class="panel-footer">
					
	
					{{ Form::open( array( "class" => "options" ) ) }}
						<div class="btn-toolbar">
							<button class="btn btn-sm btn-default" type="submit" name="writeServerConfig" value="{{ $website->id }}">
								<i class="fa fa-cog"></i> @lang("hynwmanage::manage.save_webserver_config")
							</button>
							<button class="btn btn-sm btn-default" type="submit" name="recalculateStatistics" value="{{ $website->id }}">
								<i class="fa fa-cog"></i> @lang("hynwmanage::manage.recalculate_statistics")
							</button>
						</div>
					{{ Form::close() }}
	
				</div>
				@endif
			</div>
		</div>
		<div class="col-md-6">
			
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4><i class="fa fa-globe icon-highlight"></i> @choice("hynwmanage::manage.domain_name",2)</h4>
					<div class="options">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#domains" data-toggle="tab">@lang("hynwmanage::manage.list")</a></li>
							@if ($admin)
							<li><a href="#domain-add" data-toggle="tab">@lang("hynwmanage::manage.add")</a></li>
							@endif
						</ul>
					</div>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<table class="table table-striped table-hover table-condensed tab-pane active" id="domains">
							<tr>
								<th>
								
								</th>
								<th>
									@choice("hynwmanage::manage.hostname",1)
								</th>
								<th>
									@lang("hynwmanage::manage.information")
								</th>
								<th>
								
								</th>
							</tr>
							@foreach ($website->domains as $domain)
							<tr>
								<td>
									@if ($domain -> primary )
										<i class="fa fa-caret-up" data-original-title="@lang("hynwmanage::manage.domain_is_primary")"></i>
									@elseif ($domain -> redirectPrimary )
										<i class="fa fa-angle-double-right" data-original-title="@lang("hynwmanage::manage.redirect_to_primary_domain")"></i>
									@else
										<i class="fa fa-caret-right"></i>
									@endif
								</td>
								<td>
									{{ $domain->hostname }}
								</td>
								<td>
									@if ($domain -> resolvingSystemDefault)
									<span data-tooltip="@choice("hynwmanage::manage.dns_site_resolves_default",2,['hostname' => $domain -> hostname])" class="label label-info">
										@choice("hynwmanage::manage.dns_site_resolves_default",1)
									</span>
									@elseif ($domain -> resolvingSystem)
									<span data-tooltip="@choice("hynwmanage::manage.dns_site_resolves_system",2,['hostname' => $domain -> hostname])" class="label label-success">
										@choice("hynwmanage::manage.dns_site_resolves_system",1)
									</span>
									@else
									<span data-tooltip="@choice("hynwmanage::manage.dns_site_resolves_not",2,['hostname' => $domain -> hostname])" class="label label-warning">
										@choice("hynwmanage::manage.dns_site_resolves_not",1)
									</span>
									@endif
								</td>
								<td>
									@if (!$domain -> primary)
									{{ Form::open( array( "class" => "form-inline" )) }}
										{{ Form::hidden("hostname",$domain -> hostname) }}
										{{ Form::hidden("delete",1) }}
										{{ Form::submit( _("delete") , array( "class" => "btn btn-sm btn-danger" )) }}
									{{ Form::close() }}
									@endif
								</td>
							</tr>
							@endforeach
						</table>
						@if ($admin)
						<div id="domain-add" class="tab-pane row">
							{{ Form::open( array( "class" => "form-horizontal" ) ) }}
								{{ Form::hidden("add",1) }}
								<div class="form-group">
									{{ Form::label("hostname" , \Lang::choice("hynwmanage::manage.hostname",1), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::text( "hostname" , NULL, array( "class" => "form-control", "placeholder" => _("domain.com") ) ) }}
									</div>
								</div>
								
								<div class="form-group">
									{{ Form::label("redirectPrimary" , \Lang::get("hynwmanage::manage.redirect_to_primary") , array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										<div class="checkbox block">
											<label>
												{{ Form::checkbox( "redirectPrimary" , "1" ) }}
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										{{ Form::submit(\Lang::get("hynwmanage::manage.create"), array("class" => "btn btn-primary")) }}
									</div>
								</div>
							{{ Form::close() }}
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 

