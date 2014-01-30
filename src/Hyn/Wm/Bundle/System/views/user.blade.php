
					<div id="page-heading">
						<ol class="breadcrumb">
							<li><a href="{{ URL::route("manage") }}">Dashboard</a></li>
							<li><a href="{{ URL::route("manage:users") }}">{{ _("System users") }}</a></li>
							<li><a href="{{ URL::route("manage:user" , $user -> id ) }}">{{ $user -> username }}</a></li>
						</ol>

						<h1>{{ $user -> username }}</h1>
					</div> 

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4><img src="//www.gravatar.com/avatar/{{ md5($user -> email) }}.png?s=25" /> {{ _("about") }} {{ $user->username }}</h4>
					<div class="options">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#about" data-toggle="tab">{{ _("Show") }}</a></li>
							@if (Auth::user() -> systemAdmin)
							<li><a href="#about-edit" data-toggle="tab">{{ _("Edit") }}</a></li>
							@endif
						</ul>
					</div>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<table class="table table-striped table-hover table-condensed tab-pane active" id="about">
							<tr>
								<th>
									{{ _("Unique ID") }}
								</th>
								<td>
									{{ $user -> getUserID() }}
								</td>
							</tr>
							<tr>
								<th>
									{{ _("E-mail address") }}
								</th>
								<td>
									{{ $user -> email }}
								</td>
							</tr>
							<tr>
								<th>
									{{ _("Since") }}
								</th>
								<td>
									<span class="label label-default pull-right">{{ $user -> age }}</span>{{ $user -> created_at }}
								</td>
							</tr>
						</table>
						@if (Auth::user() -> systemAdmin)
						<div class="tab-pane" id="about-edit">
							{{ Form::open( array( "class" => "form-horizontal" ) ) }}
								{{ Form::hidden( "add" , 1 ) }}
								<div class="form-group">
									{{ Form::label("right" , _("right"), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::select( "right" , $possibleRights, NULL , array( "class" => "form-control" ) ) }}
									</div>
								</div>
								<div class="form-group">
									{{ Form::label("item" , _("item"), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::text( "item" , NULL, array( "class" => "form-control", "placeholder" => _("unique ID of item") ) ) }}
									</div>
								</div>
								<div class="form-group">
									{{ Form::label("level" , _("level"), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::text( "level" , NULL, array( "class" => "form-control", "placeholder" => _("0 - 255; higher is more") ) ) }}
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										{{ Form::submit(_("add"), array("class" => "btn btn-primary")) }}
									</div>
								</div>
							{{ Form::close() }}
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-gray">
				<div class="panel-heading">
					<h4><i class="fa fa-unlock-alt icon-highlight icon-highlight-primary"></i> {{ _("rights") }}</h4>
					<div class="options">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#rights" data-toggle="tab">{{ _("List") }}</a></li>
							@if (Auth::user() -> systemAdmin)
							<li><a href="#right-add" data-toggle="tab">{{ _("Add") }}</a></li>
							@endif
						</ul>
					</div>
				</div>
				<div class="panel-body">
					<div class="tab-content">
						<table class="table table-striped table-hover table-condensed tab-pane active" id="rights">
							<tr>
								<th>
								
								</th>
								<th>
									{{ _("Right") }}
								</th>
								<th>
									{{ _("Item") }}
								</th>
								<th>
									{{ _("Level") }}
								</th>
								<th>
									
								</th>
							</tr>
							@foreach ($user -> rights() as $right)
							<tr>
								<td></td>
								<td>
									{{ $right -> right }}
								</td>
								<td>
									{{ $right -> item }}
								</td>
								<td>
									{{ $right -> level }}
								</td>
								<td>
									{{ Form::open( array( "class" => "form-inline" )) }}
										{{ Form::hidden("right",$right -> right) }}
										{{ Form::hidden("delete",1) }}
										{{ Form::hidden("idright",$right -> id) }}
										{{ Form::submit( _("delete") , array( "class" => "btn btn-sm btn-danger" )) }}
									{{ Form::close() }}
								</td>
							</tr>
							@endforeach
						</table>
						@if (Auth::user() -> systemAdmin)
						<div class="tab-pane" id="right-add">
							{{ Form::open( array( "class" => "form-horizontal" ) ) }}
								{{ Form::hidden( "add" , 1 ) }}
								<div class="form-group">
									{{ Form::label("right" , _("right"), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::select( "right" , $possibleRights, NULL , array( "class" => "form-control" ) ) }}
									</div>
								</div>
								<div class="form-group">
									{{ Form::label("item" , _("item"), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::text( "item" , NULL, array( "class" => "form-control", "placeholder" => _("unique ID of item") ) ) }}
									</div>
								</div>
								<div class="form-group">
									{{ Form::label("level" , _("level"), array( "class" => "col-sm-2 control-label" )) }}
									<div class="col-sm-10">
										{{ Form::text( "level" , NULL, array( "class" => "form-control", "placeholder" => _("0 - 255; higher is more") ) ) }}
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										{{ Form::submit(_("add"), array("class" => "btn btn-primary")) }}
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

