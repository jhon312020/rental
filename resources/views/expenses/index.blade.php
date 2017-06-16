@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.expense_lists') }}
@endsection

@section('contentheader_title')
	{{ trans('message.expense_lists') }}
@endsection

@section('main-content')
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ trans('message.expense_lists') }}</div>
					<div class="panel-body">
						<!-- will be used to show any messages -->
						@include('layouts.common.messages')
						<div class="row min-height">
							<div class="col-sm-12" height="60px">
								<a href="{{ URL::to('expenses/create') }}" class="btn btn-primary pull-right">{{ trans('message.new_expense') }}</a>
							</div>
						</div>
						<div class="row text-center">
					    <div class="col-sm-12">
					    	<div class="col-sm-4 col-sm-offset-4">
						    	<div class="form-group">
		                <label>Expenses between:</label>

		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-calendar"></i>
		                  </div>

		                  <input type="text" class="form-control pull-right dateRange" id="reservation" value="<?php echo date('01/m/Y').' - '.date('d/m/Y'); ?>">
		                  <span class="input-group-btn">
								        <button class="btn btn-secondary searchReport" type="button"><i class="fa fa-search"></i> Search</button>
								      </span>
		                </div>
		                
			             </div>
		                <!-- /.input group -->
		               <div class="row text-center">
								    <p class="total-p"><span class="total-span">Total expense: &#8377;&nbsp;<span id="total_amount_date">{{ $total_expenses }}</span></span></p>
									</div>
	              </div>
	              
	             </div>
				    </div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered reportTable">
										<thead>
											<tr>
												<td>{{ trans('message.date_of_expense') }}</td>
												<td>{{ trans('message.expense_type') }}</td>
												<td>{{ trans('message.entry_by') }}</td>
												<td>{{ trans('message.amount') }}</td>
												<td>{{ trans('message.actions') }}</td>
											</tr>
										</thead>
										<tbody>
											@foreach($expenses as $key => $value)
												<tr>
													<td>{{ $value->date_of_expense }}</td>
													<td>{{ $value->expense_type }}</td>
													<td>{{ $value->entry_by }}</td>
													<td>{{ $value->amount }}</td>

													<!-- we will also add show, edit, and delete buttons -->
													<td>

													<!-- delete the nerd (uses the destroy method DESTROY /nerds/{id} -->
													<!-- we will add this later since its a little more complicated than the other two buttons -->

													<!-- show the nerd (uses the show method found at GET /nerds/{id} -->
													<!--<a class="btn btn-small btn-success" href="{{ URL::to('guests/' . $value->id) }}">Show this Nerd</a>-->
													<a href="{{ URL::to('expenses/' . $value->id . '/edit') }}" class="btn btn-info btn-sm">
														<span class="glyphicon glyphicon-edit"></span>
													</a>
													<a href="javascript:;" data-href="{{ URL::to('expenses/' . $value->id . '/destroy') }}" class="btn btn-danger btn-sm jsDelete">
														<span class="glyphicon glyphicon-trash"></span>
													</a>
													<!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
													</td>
												</tr>
											@endforeach
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
var reportTable = null;
var start_date = '<?php echo $start_date; ?>';
var end_date = '<?php echo $end_date; ?>';
var repot_between_date = ajax_url.get_expense_report_between_date;
var column_array = [0, 1, 2, 3];
var columns_defs = {
	"columnDefs": [
    {
    	"targets" : [0],
    	"data" : "date_of_expense"
    },
    {
    	"targets" : [1],
    	"data" : "expense_type"
    },
    {
    	"targets" : [2],
    	"data" : "entry_by"
    },
    {
    	"targets" : [3],
    	"data" : "amount"
    },
    {
    	"targets" : [4],
    	render : function ( data, type, full, meta ) {
    		//console.log(data, full)
    		var $income_url = "{{ URL::to('expenses/') }}";
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
	]
};
</script>
@endsection
