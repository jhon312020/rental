@extends('layouts.app')

@section('htmlheader_title')
	{{trans('message.guests_lists')}}
@endsection

@section('contentheader_title')
	{{trans('message.guests_lists')}}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{trans('message.guests_lists')}}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						<div class="row min-height">
							<div class="col-sm-12" height="60px">
								<a href="{{ URL::to('guests/create') }}" class="btn btn-primary pull-right">{{trans('message.new_guest')}}</a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered" id="guestTable">
										<thead>
											<tr>
												<td>{{trans('message.name')}}</td>
												<td>{{trans('message.city')}}</td>
												<td>{{trans('message.state')}}</td>
												<td>{{trans('message.email')}}</td>
												<td>{{trans('message.mobile_no')}}</td>
												<td>{{trans('message.actions')}}</td>
											</tr>
										</thead>
										<tbody>
											<?php /*@foreach($guests as $key => $value)
												<tr>
													<td>{{ $value->name }}</td>
													<td>{{ $value->city }}</td>
													<td>{{ $value->state }}</td>
													<td>{{ $value->email }}</td>
													<td>{{ $value->mobile_no }}</td>

													<td>

													<a href="{{ URL::to('guests/' . $value->id . '/edit') }}" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-edit"></span>
													</a>
													<a href="javascript:;" data-href="{{ URL::to('guests/' . $value->id . '/destroy') }}" class="btn btn-danger btn-sm jsDelete">
														<span class="glyphicon glyphicon-trash"></span>
													</a>
													</td>
												</tr>
											@endforeach */ ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var column_array = [0, 1, 2, 3, 4];
		</script>
@endsection

@section('page-js-script')
<script type="text/javascript">
$(document).ready(function() {
	var columns_defs = 
		[
	    {
	    	"targets" : [0],
	    	"data" : "name",
	    },
	    {
	    	"targets" : [1],
	    	"data" : "city"
	    },
	    {
	    	"targets" : [2],
	    	"data" : "state"
	    },
	    {
	    	"targets" : [3],
	    	"data" : "email"
	    },
	    {
	    	"targets" : [4],
	    	"data" : "mobile_no"
	    },
	    {
	    	"targets" : [5],
	    	"orderable" : false,
	    	render : function ( data, type, full, meta ) {
	    		//console.log(data, full)
	    		var $income_url = "{{ URL::to('guests/') }}";
	    		var $edit_url = $income_url + '/' + full.id + '/edit';
	    		var $delete_url = $income_url + '/' + full.id + '/destroy';
	   			return '<a href="' + $edit_url + '" class="btn btn-info btn-sm">'+
									'<span class="glyphicon glyphicon-edit"></span>'+
								'</a>'+
								'<a href="javascript:;" data-href="' + $delete_url + '" class="btn btn-danger btn-sm jsDelete" style="margin-left:5px;">'+
									'<span class="glyphicon glyphicon-trash"></span>'+
								'</a>';
	    	}
	    }
		];
	var url = ajax_url.get_guests;
	commonFunctions.ajaxDataTable(columns_defs, url, 'guestTable');
});
</script>
@endsection