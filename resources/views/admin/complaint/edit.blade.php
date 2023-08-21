@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
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
				<h2>Update Complaint</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view complaint'))
					<li><a href="{{ route('view-all-complaints') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-complaint-register-details',Crypt::encrypt($complaint_details->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}


					<div class="row">

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="client_id" id="client_id" required>
										<option value=""> Please select a client </option>

										<?php foreach ($clients as $client): ?>
										<option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id',$complaint_details->client->name) == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
										<?php endforeach; ?>
									</select>

									<label class="form-label">Select client</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">

							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="branch" id="branch" required>
										<option value=""> Please select a branch </option>
										<option value="{{$complaint_branch->branch_name}}" selected>{{$complaint_branch->branch_name}}</option>
										@foreach ($f_branch as $cp)
						                    	<option value="{{ $cp->branch_name }}" {{ (old('branch',$complaint_branch->branch_name) == $cp->branch_name) ? 'selected' : '' }}>{{ ucwords($cp->branch_name) }}</option>
									    @endforeach

									</select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>


							

						</div>

					</div>

					<div class="row auto_hide">
						<div class="col-md-12">
							<div class="body table-responsive">
								<table class="table" id="contact_person_details">
									<thead>
										<tr>
											<th>Contact Person name</th>
											<th>Contact Person email</th>
											<th>Contact Person phone no</th>
										</tr>
									</thead>
									<tbody>

										@if($complaint_details->contact_persons_value == 1)
										<tr>
											<th scope="row">
												<input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="1" {{ $complaint_details->contact_persons_value == 1 ? 'checked' : '' }}>


												<label for="contact_person_1">
													<input type="text" name="c_p_1_name" value="{{ old('c_p_1_name',$complaint_details->contact_person_name) }}">
												</label>
											</th>
											<td>
												<input type="text" name="c_p_1_email" value="{{ old('c_p_1_email',$complaint_details->contact_person_email) }}">
											</td>
											<td>
												<input type="text" name="c_p_1_ph_no" value="{{ old('c_p_1_ph_no',$complaint_details->contact_person_ph_no) }}" maxlength="10">
											</td>
										</tr>
										@else
										<tr>
											<th scope="row">
												<input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="1" @if(old('contact_person_details') == "1")
												{{ "checked" }}
												@endif >


												<label for="contact_person_1">
													<input type="text" name="c_p_1_name" value="{{ old('c_p_1_name',$complaint_branch->contact_person_1_name) }}">
												</label>
											</th>
											<td>
												<input type="text" name="c_p_1_email" value="{{ old('c_p_1_email',$complaint_branch->contact_person_1_email) }}">
											</td>
											<td>
												<input type="text" name="c_p_1_ph_no" value="{{ old('c_p_1_ph_no',$complaint_branch->contact_person_1_ph_no) }}" maxlength="10">
											</td>
										</tr>
										@endif

										@if($complaint_details->contact_persons_value == 2)
										<tr>
											<th scope="row">
												<input type="radio" name="contact_person_details" id="contact_person_2" class="with-gap"  value="2" {{ $complaint_details->contact_persons_value == 2 ? 'checked' : '' }}>

												<label for="contact_person_2"><input type="text" name="c_p_2_name" value="{{ old('c_p_2_name',$complaint_details->contact_person_name) }}">
												</label>
											</th>
											<td>
												<input type="text" name="c_p_2_email" value="{{ old('c_p_2_email',$complaint_details->contact_person_email) }}">
											</td>
											<td>
												<input type="text" name="c_p_2_ph_no" value="{{ old('c_p_2_ph_no',$complaint_details->contact_person_ph_no) }}" maxlength="10">
											</td>
										</tr>
										@else
										<tr>
											<th scope="row">
												<input type="radio" name="contact_person_details" id="contact_person_2" class="with-gap"  value="2" @if(old('contact_person_details') == "2")
												{{ "checked" }}
												@endif>

												<label for="contact_person_2"><input type="text" name="c_p_2_name" value="{{ old('c_p_2_name',$complaint_branch->contact_person_2_name) }}">
												</label>
											</th>
											<td>
												<input type="text" name="c_p_2_email" value="{{ old('c_p_2_email',$complaint_branch->contact_person_2_email) }}">
											</td>
											<td>
												<input type="text" name="c_p_2_ph_no" value="{{ old('c_p_2_ph_no',$complaint_branch->contact_person_2_ph_no) }}" maxlength="10">
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="complaint_call_date" placeholder="Complaint call date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="@if($complaint_details->complaint_call_date != '0000-00-00'){{ old('complaint_call_date',date('d-m-Y', strtotime($complaint_details->complaint_call_date))) }}@endif">
									<label class="form-label">Complaint call date</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="priority" id="priority" required>
										<option value="0" {{ old('priority', $complaint_details->priority) == "0" ? 'selected' : '' }}> No priority </option>
										<option value="1" {{ old('priority', $complaint_details->priority) == "1" ? 'selected' : '' }}> Low priority </option>
										<option value="2" {{ old('priority', $complaint_details->priority) == "2" ? 'selected' : '' }}> High priority </option>
									</select>
									<label class="form-label">Complaint priority</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="group_id" id="group_id" required>
										<option value=""> Please select a group </option>
										<?php foreach ($groups as $group): ?>
										<option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id',$complaint_details->group_id) == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
										<?php endforeach; ?>
									</select>
									<label class="form-label">Select group</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="complaint_master_id" id="complaint_master_id" required>

										<option value=""> Please select a complaint </option>

										<?php foreach ($c_masters as $c_master): ?>
										<option value="{{ $c_master->id }}" data-themeid="{{ $c_master->id }}" {{ old('complaint_master_id',$complaint_details->complaint_master_id) == "$c_master->id" ? 'selected' : '' }}>{{ ucwords($c_master->complaint_details) }}</option>
										<?php endforeach; ?>

 									</select>
 									<label class="form-label">Select complaint master</label>
								</div>
							</div>
						</div>

						<div class="col-md-6" id="nt_in_list_hide">
							<div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="not_in_the_list_detail" value="{{ old('not_in_the_list_detail',$complaint_details->not_in_the_list_detail) }}">
                                    <label class="form-label">Detail complaint master</label>
                                </div>
                            </div>
						</div>

					</div>

					@if(count($product_details) >0)
					<div class="row auto_hide_product" >
						<div class="col-md-12">
							<div class="body table-responsive">
								<table class="table" id="product_details">
									<thead>
										<tr>
											<th>Product name</th>
											<th>Code</th>
											<th>Model no</th>
											<th>Serial no</th>
											<th>Brand</th>
											<th>Equipment no</th>
											<th>Date of install</th>
										</tr>
									</thead>
									<tbody>
										@foreach($product_details as $product_detail)
											<tr>
												<th scope="row">
													<div class="form-group">
														<div class="form-line">
													<input type="radio" name="product_id" id="product_id{{$product_detail->id}}" class="with-gap" value="{{ old('product_id',$product_detail->id) }}"
													 {{ $product_detail->id == $complaint_details->product_id ? 'checked' : '' }}><label for="product_id{{$product_detail->id}}">{{ $product_detail->name }}</label>
														</div>
													</div>
												</th>
												<td>{{ $product_detail->product_code }}</td>
												<td>{{ $product_detail->model_no }}</td>
												<td>{{ $product_detail->serial_no }}</td>
												<td>{{ $product_detail->brand }}</td>
												<td>{{ $product_detail->equipment_no }}</td>
												<td>
													@if($product_detail->date_of_install != '0000-00-00')
													{{ $product_detail->date_of_install }}
													@endif
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					@endif


					<div class="row">

						<div class="col-md-12">
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea class="form-control" name="complaint_details" required>{{ old('complaint_details',$complaint_details->complaint_details) }}</textarea>
                                        <label class="form-label">Complaint Details</label>
                                    </div>
                                </div>
						</div>

					</div>


					<button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
					 @if($complaint_details->complaint_status == 1 || $complaint_details->complaint_status == 4)
	                     <a href="{{ route('assigned-complaint-to-engineer',Crypt::encrypt($complaint_details->id)) }}" class="btn btn-warning btn-sm pull-right"><i class="fa fa-user"></i> Assign to engineer </a>
	                 @endif

				</form>

				<div class="row">
					<div class="col-md-12">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>

<script>

	

$('#nt_in_list_hide').hide();

  $('.auto_hide').hide();
  $('.auto_hide_product').hide();
 

 @if ($complaint_details->not_in_the_list_detail) 
 {
	$('#nt_in_list_hide').show();
 }
 @endif

 @if($complaint_details->client_id )
 {
 	$('.auto_hide').show();
 }
 @endif

 @if($complaint_details->group_id )
 {
 	$('.auto_hide_product').show();
 }
 @endif

$('.datepicker').Zebra_DatePicker({
// direction: 1,
format: 'd-m-Y',
direction: false
});

	$("#client_id").change(function(){

		var client_id = $('option:selected', this).attr('data-themeid');

// alert(client_id);


$.ajax({
	type: "GET",
	url: "{{ route('get-all-branch-details.ajax') }}",
	data: {
		'client_id': client_id
	},

	success: function(response) {
		if(response) {

			var toAppend = '';

			toAppend +='<option value="">All Branches</option>';
			$.each(response, function(i,o){

				console.log(o.branch_name);

				toAppend += '<option value="'+o.branch_name+'" data-themeid="'+o.branch_name+'">'+o.branch_name+'</option>';
			});

			$('#branch').html(toAppend);

		}else{
			alert("No branch found");
		}
	}
});
});


	$("#branch").change(function(){

		var branch = $('option:selected', this).attr('data-themeid');

		var e = document.getElementById("client_id");
		var client_id = e.options[e.selectedIndex].value;



		$.ajax({
			type: "GET",
			url: "{{ route('get-contact-person-details.ajax') }}",
			data: {
				'branch': branch,
				'client_id': client_id
			},

			success: function(response) {
				if(response) {

					var toAppend = '';

					toAppend +='<tr><th>Contact Person name</th><th>Contact Person email</th><th>Contact Person phone no</th></tr>';
					$.each(response, function(i,o){

// console.log(o.branch_name);

// toAppend += '<tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="'+o.contact_person_1_name+'#'+o.contact_person_1_email+'#'+o.contact_person_1_ph_no+'"><label for="contact_person_1">'+o.contact_person_1_name+'</label></th><td>'+o.contact_person_1_email+'</td><td>'+o.contact_person_1_ph_no+'</td></tr><tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_2" class="with-gap"  value="'+o.contact_person_2_name+'#'+o.contact_person_2_email+'#'+o.contact_person_2_ph_no+'"><label for="contact_person_2">'+o.contact_person_2_name+'</label></th><td>'+o.contact_person_2_email+'</td><td>'+o.contact_person_2_ph_no+'</td></tr>';

		  c_p_1_name = '';
          if (o.contact_person_1_name != null) 
          {
            c_p_1_name = o.contact_person_1_name;
          }

          c_p_1_email = '';
          if (o.contact_person_1_email != null) 
          {
            c_p_1_email = o.contact_person_1_email;
          }
          c_p_1_ph_no = '';
          if (o.contact_person_1_ph_no != null) 
          {
            c_p_1_ph_no = o.contact_person_1_ph_no;
          }
          c_p_2_name = '';
          if (o.contact_person_2_name != null) 
          {
            c_p_2_name = o.contact_person_2_name;
          }
          c_p_2_email = '';
          if (o.contact_person_2_email != null) 
          {
            c_p_2_email = o.contact_person_2_email;
          }
          c_p_2_ph_no = '';
          if (o.contact_person_2_ph_no != null) 
          {
            c_p_2_ph_no = o.contact_person_2_ph_no;
          }

toAppend += '<tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="1"><label for="contact_person_1"><input type="text" name="c_p_1_name" value="'+c_p_1_name+'"></label></th><td><input type="text" name="c_p_1_email" value="'+c_p_1_email+'"></td><td><input type="text" name="c_p_1_ph_no" value="'+c_p_1_ph_no+'" maxlength="10"></td></tr><tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_2" class="with-gap"  value="2"><label for="contact_person_2"><input type="text" name="c_p_2_name" value="'+c_p_2_name+'"></label></th><td><input type="text" name="c_p_2_email" value="'+c_p_2_email+'"></td><td><input type="text" name="c_p_2_ph_no" maxlength="10"></td></tr>';

});

					$('#contact_person_details').html(toAppend);
					$('.auto_hide').show();

				}else{
					alert("No contact person found");
				}
			}
		});
	});


	$("#group_id").change(function(){

		var group_id = $('option:selected', this).attr('data-themeid');

// alert(group_id);


$.ajax({
	type: "GET",
	url: "{{ route('get-all-complaint-master-details.ajax') }}",
	data: {
		'group_id': group_id
	},

	success: function(response) {

// console.log(response.complaint_master_details);

if(response) {

	var toAppend = '';
	var toAppendProduct = '';

	if(response.product_details == ''){
		toAppendProduct.hide();
	}

	// <?php if(old('group_id')!=""){?>
	// $(document).ready(function(){
	// 	$('.auto_hide_product').show();
	// });
	// <?php } ?>


	toAppend +='<option value="">All Complaint Masters</option>';

	toAppendProduct +='<tr><th>Product name</th><th>Code</th><th>Model no</th><th>Serial no</th><th>Brand</th><th>Equipment no</th><th>Date of install</th></tr>';

	$.each(response.complaint_master_details, function(i,o){

		// console.log(o.complaint_details);

		toAppend += '<option value="'+o.id+'" data-themeid="'+o.id+'">'+o.complaint_details+'</option>';
	});

	toAppend +='<option value="1">Others</option>';


	$.each(response.product_details, function(i,o){

		console.log(o.product_details);

// toAppendProduct += '<tr><td>'+o.name+'</td><td>'+o.product_code+'</td><td>'+o.model_no+'</td><td>'+o.serial_no+'</td><td>'+o.brand+'</td>><td>'+o.equipment_no+'</td></tr>';

		date_of_install = '';
          if (o.date_of_install != '0000-00-00')
          {
            date_of_install = o.date_of_install;
          }
         
          code = '';
          if (o.product_code != null) 
          {
          	code = o.product_code;
          }

          equipment_no = '';
          if (o.equipment_no != null) 
          {
          	equipment_no = o.equipment_no;
          }


toAppendProduct += '<tr><th scope="row"><div class="form-group"><div class="form-line"><input type="radio" name="product_id" id="product_id'+o.id+'" class="with-gap" value="'+o.id+'"><label for="product_id'+o.id+'">'+o.name+'</label></div></div></th><td>'+o.product_code+'</td><td>'+o.model_no+'</td><td>'+o.serial_no+'</td><td>'+o.brand+'</td><td>'+o.equipment_no+'</td><td>'+date_of_install+'</td></tr>';
});

	$('#complaint_master_id').html(toAppend);
	$('#product_details').html(toAppendProduct);
	$('.auto_hide_product').show();
	$("input[id^='product_id']").prop("required", true);

}else{
	alert("No complaint master found");
}
}
});
});

$('#complaint_master_id').on('change', function() {
 	if($(this).val() == 1)
 		$('#nt_in_list_hide').show();
 	else
 		$('#nt_in_list_hide').hide();
 });
</script>
@stop

