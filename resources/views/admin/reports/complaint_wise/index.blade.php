@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
<style>
	.form-group .form-line .form-label {
		top: -10px!important;
		font-size: 12px !important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Search complaints by</h2>
				<ul class="header-dropdown m-r--5">
					{{-- <li><a href="{{ route('view-all-groups') }}" class="btn btn-success">View all</a></li> --}}
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="get" action="{{ route('view-complaint-reports.store') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="from_date" name="from_date" data-zdp_readonly_element="false" value="{{ old('from_date') }}">
									<label class="form-label">Date from</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="to_date" name="to_date" data-zdp_readonly_element="false" value="{{ old('to_date') }}">
									<label class="form-label">Date to</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="zone_id" id="zone_id" >
										<option value=""> Please select a zone </option>

										@foreach ($zones as $zone)
						                    	<option value="{{ $zone->id }}" {{ (old('zone_id') == $zone->id) ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
						               @endforeach
										
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="client_id" id="client_id" >
										<option value=""> Please select a client </option>
										<?php foreach ($clients as $client): ?>
										<option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id') == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
										<?php endforeach; ?>
									</select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
			                    <div class="form-line">
			                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" autocomplete="off">
			                        <label class="form-label">Branch name</label>
			                        @if(count($clients) > 0)
			                        <datalist id="options_branch">
			                          @foreach($clients->unique("branch_name") as $c_group)
			                          <option value="{{$c_group->branch_name}}"></option>
			                          @endforeach
			                        </datalist>
			                        @endif
			                    </div>
			                </div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="priority" id="priority" >
										<option value="">Select priority</option>
										<option value="0"> No priority </option>
										<option value="1"> Low priority </option>
										<option value="2"> High priority </option>
									</select>
									<label class="form-label">Complaint priority</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="group_id" id="group_id" >
										<option value=""> Please select a group </option>
										<?php foreach ($groups as $group): ?>
										<option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
										<?php endforeach; ?>
									</select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="complaint_master_id" id="complaint_master_id" >
										<option value=""> Please select a complaint type </option>

										@foreach ($c_masters as $c_master)
						                    	<option value="{{ $c_master->id }}" {{ (old('complaint_master_id') == $c_master->id) ? 'selected' : '' }}>{{ ucwords($c_master->complaint_details) }}</option>
						               @endforeach
										
									</select>
								</div>
							</div>
						
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="product_id" id="product_id" >
										<option value=""> Please select a product </option>

										@foreach ($products as $product)
						                    	<option value="{{ $product->id }}" {{ (old('product_id') == $product->id) ? 'selected' : '' }}>{{ ucwords($product->name) }}</option>
						               @endforeach
										
									</select>
								</div>
							</div>
						
						</div>
					</div>

					<button class="btn btn-primary waves-effect" type="submit">SEARCH</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
<script>
	$('#from_date').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false,
	      
	 });

	$('#to_date').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false,
	      
	 });

// $("#client_id").change(function(){

// 	var client_id = $('option:selected', this).attr('data-themeid');

// 	$.ajax({
// 	type: "GET",
// 	url: "{{ route('get-all-branch-details.ajax') }}",
// 	data: {
// 		'client_id': client_id
// 	},

// 	success: function(response) {
// 		if(response) {

// 			var toAppend = '';

// 			toAppend +='<option value="">All Branches</option>';
// 			$.each(response, function(i,o){

// 				console.log(o.branch_name);

// 				toAppend += '<option value="'+o.branch_name+'" data-themeid="'+o.branch_name+'">'+o.branch_name+'</option>';
// 			});

// 			$('#branch').html(toAppend);

// 		}else{
// 			alert("No branch found");
// 		}
// 	}
// });
// });
</script>
@stop

