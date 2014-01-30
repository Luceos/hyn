
					<div id="page-heading">
						<ol class="breadcrumb">
							<li class='active'><a href="{{ URL::route("manage") }}">Dashboard</a></li>
						</ol>

						<h1>{{ _("Dashboard") }}</h1>
					</div>


					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3 col-xs-12 col-sm-6">
										<a class="info-tiles tiles-toyo" href="{{ URL::route("manage:websites") }}">
											<div class="tiles-heading">{{ _("Domains") }}</div>
											<div class="tiles-body-alt">
												<i class="fa fa-globe"></i>
												<div class="text-center">{{ $domains }}</div>
												<small>{{ _("on") }} {{ $websites }} {{ _("sites") }}</small>
											</div>
											<div class="tiles-footer">{{ _("manage sites") }}</div>
										</a>
									</div>
									<div class="col-md-3 col-xs-12 col-sm-6">
										<a class="info-tiles tiles-success" href="#">
											<div class="tiles-heading">{{ _("Revenue") }}</div>
											<div class="tiles-body-alt">
												<i class="fa fa-money"></i>
												<div class="text-center"><span class="text-top">&euro;</span>0<span class="text-smallcaps">k</span></div>
												<small>-13.5% from last week</small>
											</div>
											<div class="tiles-footer">{{ _("view subscriptions") }}</div>
										</a>
									</div>
									<div class="col-md-3 col-xs-12 col-sm-6">
										<a class="info-tiles tiles-orange" href="{{ URL::route("manage:system") }}">
											<div class="tiles-heading">{{ _("System") }}</div>
											<div class="tiles-body-alt">
												<i class="fa fa-sitemap"></i>
												<div class="text-center">tba</div>
												<small>{{ _("system performance" ) }}</small>
											</div>
											<div class="tiles-footer">{{ _("complete system" ) }}</div>
										</a>
									</div>
									<div class="col-md-3 col-xs-12 col-sm-6">
										<a class="info-tiles tiles-alizarin" href="#">
											<div class="tiles-heading">-</div>
											<div class="tiles-body-alt">
											<i class="fa fa-shopping-cart"></i>
											<div class="text-center">tba</div>
											<small>-</small>
											</div>
											<div class="tiles-footer">-</div>
										</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 col-xs-12 col-sm-6">
										<a class="info-tiles tiles-green" href="{{ URL::route("manage:users") }}">
											<div class="tiles-heading">{{ _("System users") }}</div>
											<div class="tiles-body-alt">
												<i class="fa fa-users"></i>
												<div class="text-center">{{ $systemusers }}</div>
												<small></small>
											</div>
											<div class="tiles-footer">{{ _("manage users") }}</div>
										</a>
									</div>
									
								</div>
							</div>
						</div>
					</div> 
