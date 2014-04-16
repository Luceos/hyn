
					<div id="page-heading">
						<ol class="breadcrumb">
							<li><a href="{{ URL::route("manage") }}">Dashboard</a></li>
							<li class="active"><a href="{{ URL::route("manage:websites") }}">@lang("hynwmanage::manage.system")</a></li>
						</ol>

						<h1>@lang("hynwmanage::manage.system")</h1>
					</div>


					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<div class="panel panel-primary">
									<div class="panel-body">
										
										<div class="col-xs-6 col-sm-6 col-md-4">
											<div class="easypiechart" id="diskusage" data-percent="{{ System::PHPMemoryUsagePercentage() }}" data-size="90">
												<span class="percent">{{ System::PHPMemoryUsagePercentage() }}</span>
											</div>
											<label for="diskusage">@lang("hynwmanage::manage.memory_usage")</label>
											<hr class="visible-sm visible-xs">
										</div>
										<div class="col-xs-6 col-sm-6 col-md-4">
											<div class="easypiechart" id="diskusage" data-percent="{{ System::diskSpaceUsagePercentage() }}" data-size="90">
												<span class="percent">{{ System::diskSpaceUsagePercentage() }}</span>
											</div>
											<label for="diskusage">@lang("hynwmanage::manage.disk_usage")</label>
											<hr class="visible-sm visible-xs">
										</div>
										<div class="col-xs-6 col-sm-6 col-md-4">
											<div class="easypiechart" id="diskusage" data-percent="{{ System::systemLoadPercentage() }}" data-size="90">
												<span class="percent">{{ System::systemLoadPercentage() }}</span>
											</div>
											<label for="diskusage">@lang("hynwmanage::manage.load")</label>
											<hr class="visible-sm visible-xs">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> 
 
