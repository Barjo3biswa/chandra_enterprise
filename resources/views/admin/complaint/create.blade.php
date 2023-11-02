@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
	.form-group .form-line .form-label {
		top: -10px!important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add New Complaint</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view complaint'))
					<li><a href="{{ route('view-all-complaints') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-complaint.post') }}">
					{{ csrf_field() }}


					<div class="row">

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="client_id" id="client_id" required>
										<option value=""> Please select a client </option>
										<?php foreach ($clients as $client): ?>
										<option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id') == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
										<?php endforeach; ?>
									</select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="branch" id="branch" required>
										<option value=""> Please select a branch </option>

										@foreach ($clients as $cp)
						                    @if($cp->branch_name==old('branch'))
						                    	<option value="{{ $cp->branch_name }}" {{ (old('branch') == $cp->branch_name) ? 'selected' : '' }}>{{ ucwords($cp->branch_name) }}</option>
						                    @endif
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
										
										
										<tr>
											<th scope="row">
												<input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap contact_person_details" value="1" 
												@if(old('contact_person_details') == "1")
												{{ "checked" }}
												@endif
												><label for="contact_person_1"><input type="text" class="form-control" name="contact_person_name" value="{{ old('contact_person_name') }}" placeholder="Contact person1 name"></label>
											</th>
											<td>
												<input type="text" class="form-control" name="c_p_1_email" value="{{ old('c_p_1_email') }}" placeholder="Contact person1 email">
											</td>
											<td>
												<input type="text" class="form-control" name="c_p_1_ph_no" value="{{ old('c_p_1_ph_no') }}" maxlength="10" placeholder="Contact person1 ph no">
											</td>
										</tr>

										<tr>
											<th scope="row">
												<input type="radio" name="contact_person_details" id="contact_person_2" class="with-gap contact_person_details"  value="2"
												@if(old('contact_person_details') == "2")
												{{ "checked" }}
												@endif
												><label for="contact_person_2"><input type="text" class="form-control" name="c_p_2_name" value="{{ old('c_p_2_name') }}" placeholder="Contact person2 name"></label>
											</th>
											<td>
												<input type="text" class="form-control" name="c_p_2_email" value="{{ old('c_p_2_email') }}" placeholder="Contact person2 email">
											</td>
											<td>
												<input type="text" class="form-control" name="c_p_2_ph_no" value="{{ old('c_p_2_ph_no') }}" maxlength="10" placeholder="Contact person2 ph no">
											</td>
										</tr>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="complaint_call_date" placeholder="Complaint call date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('complaint_call_date') }}">
									<label class="form-label">Complaint call date</label>
								</div>
							</div>
						</div>


						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="priority" id="priority" required>
										<option value="0"> No priority </option>
										<option value="1"> Low priority </option>
										<option value="2"> High priority </option>
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
										<option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
										<?php endforeach; ?>
									</select>
									<label class="form-label">Select Group</label>
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

										@foreach ($c_masters as $c_master)
						                    @if($c_master->id==old('complaint_master_id'))
						                    	<option value="{{ $c_master->id }}" {{ (old('complaint_master_id') == $c_master->id) ? 'selected' : '' }}>{{ ucwords($c_master->complaint_details) }}</option>
						                    @endif
					                    @endforeach
										
									</select>
									<label class="form-label">Select Complaint Master</label>
								</div>
							</div>
						</div>

						<div class="col-md-6" id="nt_in_list_hide">
							<div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="not_in_the_list_detail" value="{{ old('not_in_the_list_detail') }}">
                                    <label class="form-label">Detail complaint master</label>
                                </div>
                            </div>
						</div>

					</div>



					<div class="row auto_hide_product" >
						<div class="col-md-12">
							<div class="body table-responsive">
								<table class="table" id="product_details">
									<thead>

									</thead>
									<tbody>
										@if(old('group_id'))
										@foreach($product_details as $product_detail)
											<tr>
												<th scope="row">
													<div class="form-group">
													<div class="form-line">	
													<input type="radio" name="product_id" id="product_id{{$product_detail->id}}" class="with-gap" value="{{ old('product_id',$product_detail->id) }}"
													 @if(old('product_id') == $product_detail->id)
													{{ "checked" }}
													@endif required><label for="product_id{{$product_detail->id}}">{{ $product_detail->name }}</label>
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
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>


					<div class="row">

						<div class="col-md-12">
							<div id="error_msg">
					            <p class="text-danger">You cannot submit data as there is no product to select</p>
					        </div>
						</div>

						<div class="col-md-12">
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea class="form-control" name="complaint_details" required>{{ old('complaint_details') }}</textarea>
                                        <label class="form-label">Complaint Details</label>
                                    </div>
                                </div>
						</div>

					</div>


					<button class="btn btn-primary waves-effect cnt-submit" type="submit">SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
{{-- <script src="{!! asset('assets/js/form-validation.js')!!}"></script> --}}

<script>

// $(function () {
//     $('#form_validation').validate({
//         rules: {
//             'product_id': {
//                 required: true
//             },
            
//         },
//         highlight: function (input) {
//             $(input).parents('.form-line').addClass('error');
//         },
//         unhighlight: function (input) {
//             $(input).parents('.form-line').removeClass('error');
//         },
//         errorPlacement: function (error, element) {
//             $(element).parents('.form-group').append(error);
//         }
//     });
//  });

$('#nt_in_list_hide').hide();
$('#error_msg').hide();
$('.cnt-submit').hide();
$(".cnt-submit").prop("disabled", true);
$("input[id^='product_id']").prop("required", true);

	<?php if(old('branch')!=""){?>
	$(document).ready(function(){
		$('.auto_hide').show();
	});
	<?php } ?>

	<?php if(old('group_id')!=""){?>
	$(document).ready(function(){
		$('.auto_hide_product').show();
	});
	<?php } ?>
//console.log(old('branch'));

		


// $(document).ready(function(){
  $('.auto_hide').hide();
  $('.auto_hide_product').hide();
 var now = new Date();
 
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    // var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    var today = day+"-"+month+"-"+now.getFullYear() ;
    $('.datepicker').val(today);
// });

$('.datepicker').Zebra_DatePicker({
// direction: 1,
format: 'd-m-Y',
direction: false
});

	$("#client_id").change(function(){
		var client_id = $('option:selected', this).attr('data-themeid');
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
		var branch    = $('option:selected', this).attr('data-themeid');
		var e 		  = document.getElementById("client_id");
		var client_id = e.options[e.selectedIndex].value;

		$.ajax({
			type: "GET",
			url: "{{ route('get-contact-person-details.ajax') }}",
			data: {
				'branch': branch,
				'client_id': client_id
			},
			success: function(response) {
				console.log(response);
				if(response) {
					var toAppend = '';
					toAppend +='<tr><th>Contact Person name</th><th>Contact Person email</th><th>Contact Person phone no</th></tr>';
					$.each(response, function(i,o){
						contact_person_name = '';
						if (o.contact_person_1_name != null) {
							contact_person_name = o.contact_person_1_name;
						}

						c_p_1_email = '';
						if (o.contact_person_1_email != null)  {
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
						toAppend += '<tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="1"><label for="contact_person_1"><input type="text" class="form-control" name="contact_person_name" value="'+contact_person_name+'" placeholder="contact person1 name"></label></th><td><input type="text" class="form-control" name="c_p_1_email" value="'+c_p_1_email+'" placeholder="contact person1 email"></td><td><input type="text" class="form-control" name="c_p_1_ph_no" value="'+c_p_1_ph_no+'" placeholder="contact person1 ph_no" maxlength="10"></td></tr><tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_2" class="with-gap" value="2"><label for="contact_person_2"><input type="text" class="form-control" name="c_p_2_name" value="'+c_p_2_name+'" placeholder="contact person2 name"></label></th><td><input type="text" class="form-control" name="c_p_2_email" value="'+c_p_2_email+'" placeholder="contact person2 email"></td><td><input type="text" class="form-control" name="c_p_2_ph_no" placeholder="contact person2 ph_no" maxlength="10" value="'+c_p_2_ph_no+'"></td></tr>';
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
		var e = document.getElementById("client_id");
		var client_id = e.options[e.selectedIndex].value;
		var b = document.getElementById("branch");
		var branch = b.options[b.selectedIndex].value;
		$.ajax({
			type: "GET",
			url: "{{ route('get-all-complaint-master-details.ajax') }}",
			data: {
				'group_id': group_id,
				'client_id': client_id,
				'branch': branch
			},
			success: function(response) {
				if(response) {
					var toAppend = '';
					var toAppendProduct = '';
					if (response.complaint_master_details.length) {
						toAppend +='<option value="">All Complaint Masters</option>';
						$.each(response.complaint_master_details, function(i,o){
							console.log(o.complaint_details);
							toAppend += '<option value="'+o.id+'" data-themeid="'+o.id+'">'+o.complaint_details+'</option>';
						});
						toAppend +='<option value="1">Not in the list</option>';
						$('#complaint_master_id').html(toAppend);
					}else{
						$('#complaint_master_id').html("");
						toAppend +='<option value="">All Complaint Masters</option>';
						toAppend +='<option value="1">Not in the list</option>';
						$('#complaint_master_id').html(toAppend);
					}
					if(!response.product_details.length){
						$('#product_details').hide();
						$('#error_msg').show();
						$('.cnt-submit').hide();
						$(".cnt-submit").prop("disabled", true);
						$("input[id^='product_id']").prop("required", true);
					
					}else{
					toAppendProduct +='<tr id="head_hide"><th>Product name</th><th>Code</th><th>Model no</th><th>Serial no</th><th>Brand</th><th>Equipment no</th><th>Date of install</th></tr>';
					$.each(response.product_details, function(i,o){
						console.log(o.product_details);
						date_of_install = '';
						if (o.date_of_install != '0000-00-00')
						{
							date_of_install = o.date_of_install;
						}
						if(o.date_of_install == null)
						{
							date_of_install = '';
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
				toAppendProduct += '<tr><th scope="row"><div class="form-group"><div class="form-line"><input type="radio" name="product_id" id="product_id'+o.id+'" class="with-gap" value="'+o.id+'"><label for="product_id'+o.id+'">'+o.name+'</label></div></div></th><td>'+code+'</td><td>'+o.model_no+'</td><td>'+o.serial_no+'</td><td>'+o.brand+'</td><td>'+equipment_no+'</td><td>'+date_of_install+'</td></tr>';
			});

			
			$('#product_details').html(toAppendProduct);
			$('.auto_hide_product').show();
			$('#product_details').show();
			$('.cnt-submit').show();
			$(".cnt-submit").prop("disabled", false);
			$("input[id^='product_id']").prop("required", true);
			$('#error_msg').hide();
			}

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

