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
				<h2>Add Daily Service Report For <span class="font-underline col-teal">{{ date('d M, Y') }}</span></h2>

				<h5>Last Report Entry Date <span class="font-underline col-teal">
					@if(isset($dsr_report->entry_datetime))
					{{ date('d-m-Y h:i A', strtotime(isset($dsr_report->entry_datetime ) ? $dsr_report->entry_datetime : "")) }}
					@endif
				</span>
			</h5>

			<ul class="header-dropdown m-r--5">
				<li><a href="{{ route('view-all-daily-service-report') }}" class="btn btn-success">View all</a></li>
			</ul>
		</div>
		<div class="body">
			<form id="form_validation" method="POST" action="{{ route('add-new-daily-service-report.store') }}">
				{{ csrf_field() }}



				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div role="tabpanel" class="tab-pane fade in active" id="home">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="client_id" id="client_id" required>
												<option value="">-- Please select client --</option>
												<?php foreach ($unique_clients as $client): ?>
													<option value="{{ $client->client->name }}" data-themeid="{{ $client->client->name }}" {{ old('client_id', $client_id) == "$client->client->name" ? 'selected' : '' }}>{{ ucwords($client->client->name) }}</option>
												<?php endforeach; ?>
												@php
												if(old("client_id")){
												$client_id = old("client_id");
											}
											@endphp

										</select>
										<label class="form-label">Select client</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<select class="form-control show-tick" name="branch" id="branch" required>
											<option value=""> Please select a branch </option>

											@foreach ($assgn_eng as $cp)
											@if($cp->name == $client_id)

											<option value="{{ $cp->client->branch_name }}" data-themeid="{{ $cp->client->branch_name }}" {{ (old('branch', $branch) == $cp->client->branch_name) ? 'selected' : '' }}>{{ ucwords($cp->client->branch_name) }}</option>
											@endif
											@endforeach


										</select>
										<label class="form-label">Select branch</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<select class="form-control" name="maintenance_type" id="maintenance_type" required>
											<option value="">-- Please select Maintenance Type --</option>
											<option value="1" data-themeid="1" {{ old('maintenance_type', $maintenance_type) == "1" ? 'selected' : '' }} >Break Down</option>
											<option value="2" data-themeid="2" {{ old('maintenance_type', $maintenance_type) == "2" ? 'selected' : '' }}>Preventive</option>
										</select>
										<label class="form-label">Select Maintenance Type</label>
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
												<th></th>
												<th>Contact Person name</th>
												<th>Contact Person phone no</th>
											</tr>
										</thead>
										<tbody>


											<tr>
												<th scope="row">
													<input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="1" 
													@if(old('contact_person_details') == "1")
													{{ "checked" }}
													@endif required 
													><label for="contact_person_1"></label>
												</th>
												<td>
													<input type="text" name="contact_person_name" value="{{ old('contact_person_name',(isset($client_contact_person_details->contact_person_1_name) ? $client_contact_person_details->contact_person_1_name  : "")) }}" placeholder="Contact person name" required>
												</td>
												<td>
													<input type="text" name="c_p_1_ph_no" value="{{ old('c_p_1_ph_no',(isset($client_contact_person_details->contact_person_1_ph_no) ? $client_contact_person_details->contact_person_1_ph_no : "" )) }}" placeholder="Contact person phone no" maxlength="10">
												</td>
											</tr>
	

										</tbody>
									</table>
								</div>
							</div>
						</div>



						{{-- ################################ Break down ###################################################### --}}


						<div class="row" id="maintenance_type_detail_complaint">

							<div class="col-md-3">
								<div class="form-group form-float">
									<div class="form-line">
										<select class="form-control" name="complaint_id" id="complaint_id">
											<option value="">-- Please select complaint --</option>
											<?php foreach ($user_assigned_complaints as $user_assigned_complaint): ?>
												<option value="{{ $user_assigned_complaint->id }}" data-themeid="{{ $user_assigned_complaint->id }}" {{ old('complaint_id') == "$user_assigned_complaint->id" ? 'selected' : '' }}>{{ ucwords($user_assigned_complaint->complaint_no) }}</option>
											<?php endforeach; ?>
										</select>
										<label class="form-label">Select complaint</label>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control datepicker" name="call_receive_date" id="call_receive_date" placeholder="Call receive date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false">
										<label class="form-label">Call receive date</label>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control" placeholder="Complaint call date eg,(dd-mm-yyyy)" value="{{ date('d-m-Y') }}" readonly>
										<input type="hidden" name="call_attend_date" value="{{ date('d-m-Y') }}">
										<label class="form-label">Call attend date</label>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group form-float">
									<div class="form-line">
										<select class="form-control show-tick" name="complaint_status" id="complaint_status" required>
											<option value=""> Complaint status </option>
											<option value="2">Complaint under-process</option>
											<option value="3"> Complaint closed </option>
										</select>
										{{-- <label class="form-label">Select Group</label> --}}
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control" name="nature_of_complaint_by_customer" id="nature_of_complaint_by_customer" value="{{ old('nature_of_complaint_by_customer') }}">
										<label class="form-label">Nature of complaint(By customer)</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control" name="fault_observation_by_engineer" id="fault_observation_by_engineer" value="{{ old('fault_observation_by_engineer') }}">
										<label class="form-label">Fault observed(By engineer)</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control" name="action_taken_by_engineer" id="action_taken_by_engineer" value="{{ old('action_taken_by_engineer') }}">
										<label class="form-label">Action taken & result(By engineer)</label>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group form-float">
									<div class="form-line">
										<textarea class="form-control" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
										<label class="form-label">Remarks if any</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<select class="form-control" name="product_id" id="product_id" required>
											<option value="">-- Please select product --</option>

										</select>
										<label class="form-label">Select product</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control" name="model_no" id="model_no" value="{{ old('model_no') }}">
										<label class="form-label">Product model no</label>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group form-float">
									<div class="form-line">
										<input type="text" class="form-control" name="serial_no" id="serial_no" value="{{ old('serial_no') }}">
										<label class="form-label">Product serial no</label>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th colspan="4">PARTS SUPPLIED /REPLACE</th>
											<th colspan="2">WORN-OUT PART TAKEN BACK</th>
											<th colspan="4">UNIT PRICE <span class="pull-right">LABOUR</span></th>
										</tr>
										<tr>
											<th>#</th>
											<th>PART NO</th>
											<th>DESCRIPTION</th>
											<th>QNTY</th>
											<th>YES/NO</th>
											<th>QNTY</th>
											<th>FREE</th>
											<th>CHARGEABLE</th>
											<th colspan="2">FREE/CHARGEABLE</th>

										</tr>
									</thead>
									<tbody>
										@for($i = 0; $i<=9; $i++)


										<tr>
											<td>{{ ($i+1) }}</td>
											<td>
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control sp_check_duplicate spare_part_id" id="spare_part_id" name="spare_part_id[]" onchange="isDuplicateAlReadySelected(this)">
															<option value="">Select spare part</option>
															@foreach($all_sp_prts as $assigned_spare_part)

															<option value="{{ $assigned_spare_part->spare_part->id }}" data-themeid="{{ $assigned_spare_part->spare_part->id }}" {{ old('spare_part_id') == "$assigned_spare_part->spare_part->id" ? 'selected' : '' }}>{{ ucwords($assigned_spare_part->spare_part->part_no) }}</option>

															@endforeach

														</select>
													</div>
												</div>

											</td>
											<td><input type="text" id="spare_part_description" name="spare_part_description[]"></td>

											<td><input style="width: 35%;" type="text" id="spare_part_quantity" name="spare_part_quantity[]" oninput="checkQuantity(this)" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>
											<td>
												<input type="checkbox" id="basic_checkboxb_1{{ $i }}" name="spare_part_taken_back[]" value="1" onchange="checkOnluOne(this)">
												<label for="basic_checkboxb_1{{ $i }}">yes</label>

												<input type="checkbox" id="basic_checkboxb_2{{ $i }}" name="spare_part_taken_back[]" value="0" onchange="checkOnluOne(this)">
												<label for="basic_checkboxb_2{{ $i }}">no</label>

											</td>
											<td><input style="width: 35%;" type="text" name="spare_part_taken_back_quantity[]" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>

											<td><input style="width: 35%;" type="text" id="unit_price_free" name="unit_price_free[]" oninput="checkQuantity(this)" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>

											<td><input style="width: 35%;" type="text" id="unit_price_chargeable" name="unit_price_chargeable[]" oninput="checkQuantity(this)" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>

											<td>
												<input type="checkbox" id="labour_freeb1{{ $i }}" name="labour_free[]" value="0" onchange="checkOnluOne(this)">
												<label for="labour_freeb1{{ $i }}">Free</label>
											{{-- </td>

												<td> --}}
													<input type="checkbox" id="labour_freeb2{{ $i }}" name="labour_free[]" value="1" onchange="checkOnluOne(this)">
													<label for="labour_freeb2{{ $i }}">Chargeable</label>
												</td>
											</tr>

											@endfor
										</tbody>
									</table>
								</div>
							</div>





							{{-- ######################################################## AMC ############################################### --}}


							<div class="row" id="maintenance_type_detail_amc">

								<div class="col-md-4" id="hide_complaint_id_amc">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="complaint_id_amc" id="complaint_id_amc" disabled>
												<option value="">-- Please select complaint --</option>
												<?php foreach ($user_assigned_complaints as $user_assigned_complaint): ?>
													<option value="{{ $user_assigned_complaint->id }}" data-themeid="{{ $user_assigned_complaint->id }}" {{ old('complaint_id_amc') == "$user_assigned_complaint->id" ? 'selected' : '' }}>{{ ucwords($user_assigned_complaint->complaint_no) }}</option>
												<?php endforeach; ?>
											</select>
											<label class="form-label">Select complaint</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control datepicker" name="call_receive_date_amc" id="call_receive_date_amc" placeholder="Call receive date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false">
											<label class="form-label">Call receive date</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" placeholder="Complaint call date eg,(dd-mm-yyyy)" value="{{ date('d-m-Y') }}" readonly>
											<input type="hidden" name="call_attend_date_amc" value="{{ date('d-m-Y') }}">
											<label class="form-label">Call attend date</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="nature_of_complaint_by_customer_amc" id="nature_of_complaint_by_customer_amc" value="{{ old('nature_of_complaint_by_customer_amc') }}">
											<label class="form-label">Nature of complaint(By customer)</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="fault_observation_by_engineer_amc" id="fault_observation_by_engineer_amc" value="{{ old('fault_observation_by_engineer_amc') }}">
											<label class="form-label">Fault observed(By engineer)</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="action_taken_by_engineer_amc" id="action_taken_by_engineer_amc" value="{{ old('action_taken_by_engineer_amc') }}">
											<label class="form-label">Action taken & result(By engineer)</label>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group form-float">
										<div class="form-line">
											<textarea class="form-control" name="remarks_amc" id="remarks_amc">{{ old('remarks_amc') }}</textarea>
											<label class="form-label">Remarks if any</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<select class="form-control" name="product_id_amc" id="product_id_preventive" required>
												<option value="">-- Please select product --</option>

											</select>
											<label class="form-label">Select product</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="model_no_amc" id="model_no_amc" value="{{ old('model_no_amc') }}">
											<label class="form-label">Product model no</label>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="serial_no_amc" id="serial_no_amc" value="{{ old('serial_no_amc') }}">
											<label class="form-label">Product serial no</label>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th colspan="4">PARTS SUPPLIED /REPLACE</th>
												<th colspan="2">WORN-OUT PART TAKEN BACK</th>
												<th colspan="4">UNIT PRICE <span class="pull-right">LABOUR</span></th>
											</tr>
											<tr>
												<th>#</th>
												<th>PART NO</th>
												<th>DESCRIPTION</th>
												<th>QNTY</th>
												<th>YES/NO</th>
												<th>QNTY</th>
												<th>FREE</th>
												<th>CHARGEABLE</th>
												<th colspan="2">FREE/CHARGEABLE</th>
											</tr>
										</thead>
										<tbody>
											@for($i = 0; $i<=9; $i++)
											<tr>
												<td>{{ $i+1 }}</td>
												<td>
													<div class="form-group form-float">
														<div class="form-line">
															<select class="form-control sp_check_duplicate spare_part_id" id="spare_part_id" name="spare_part_id_amc[]" onchange="isDuplicateAlReadySelected(this)">
																<option value="">Select spare part</option>
																@foreach($all_sp_prts as $assigned_spare_part)

																<option value="{{ $assigned_spare_part->spare_part->id }}" data-themeid="{{ $assigned_spare_part->spare_part->id }}" {{ old('spare_part_id_amc') == "$assigned_spare_part->spare_part->id" ? 'selected' : '' }}>{{ ucwords($assigned_spare_part->spare_part->part_no) }}</option>

																@endforeach

															</select>
														</div>
													</div>

												</td>
												<td><input type="text" id="spare_part_description" name="spare_part_description_amc[]"> </td>
												<td><input style="width: 35%;" type="text" id="spare_part_quantity" name="spare_part_quantity_amc[]" oninput="checkQuantity(this)" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>
												<td>
													<input type="checkbox" id="basic_checkbox_1{{ $i }}" name="spare_part_taken_back_amc[]" value="1" onchange="checkOnluOne(this)">
													<label for="basic_checkbox_1{{ $i }}">yes</label>

													<input type="checkbox" id="basic_checkbox_2{{ $i }}" name="spare_part_taken_back_amc[]" value="0" onchange="checkOnluOne(this)">
													<label for="basic_checkbox_2{{ $i }}">no</label>

												</td>
												<td><input style="width: 35%;" type="text" name="spare_part_taken_back_quantity_amc[]" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>

												<td><input style="width: 35%;" type="text" id="unit_price_free" name="unit_price_free_amc[]" oninput="checkQuantity(this)" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>

												<td><input style="width: 35%;" type="text" id="unit_price_chargeable" name="unit_price_chargeable_amc[]" oninput="checkQuantity(this)" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')"></td>

												<td>
													<input type="checkbox" id="labour_free1{{ $i }}" name="labour_free_amc[]" value="0" onchange="checkOnluOne(this)">
													<label for="labour_free1{{ $i }}">Free</label>
												{{-- </td>

													<td> --}}
														<input type="checkbox" id="labour_free2{{ $i }}" name="labour_free_amc[]" value="1" onchange="checkOnluOne(this)">
														<label for="labour_free2{{ $i }}">Chargeable</label>
													</td>
												</tr>
												@endfor
											</tbody>
										</table>
									</div>
								</div>


								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
									</div>
								</div>
							</div>
						</div>
					</div>		
				</form>
			</div>
		</div>
	</div>
</div>



@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
<script>

	<?php if($branch!=""){?>
		$(document).ready(function(){
			$('.auto_hide').show();
		});
		<?php } ?>

		<?php if($maintenance_type=='1'){?>
			$(document).ready(function(){
				$('#maintenance_type_detail_complaint').show(function(){
					$(this).find("input, select, textarea").prop("disabled", false);
				});
				$('#maintenance_type_detail_amc').hide(function(){
					$(this).find("input, select, textarea").prop("disabled", true);
				});
			});
			<?php } ?>
			<?php if($maintenance_type=='2'){?>
				$(document).ready(function(){
					$('#maintenance_type_detail_amc').show(function(){
						$(this).find("input, select, textarea").prop("disabled", false);
					});
					$('#maintenance_type_detail_complaint').hide(function(){
						$(this).find("input, select, textarea").prop("disabled", true);
					});
				});
				<?php } ?>

				$('.datepicker').Zebra_DatePicker({
// direction: 1,
format: 'd-m-Y',
direction: false
});

// $(document).ready(function(){
	$('#maintenance_type_detail_complaint').hide();
	$('#maintenance_type_detail_amc').hide();
	$('.auto_hide').hide();
// $('#comp_details').hide();
// });

$("#client_id").change(function(){

	var client_id = $('option:selected', this).attr('data-themeid');
// alert(client_id);


$.ajax({
	type: "GET",
	url: "{{ route('get-client-branch-name-details.ajax') }}",
	data: {
		'client_id': client_id
	},

	success: function(response) {
		if(response) {

			var toAppend = '';

			toAppend +='<option value="">All Branches</option>';
			$.each(response, function(i,o){

				console.log(o.branch_name);

				toAppend += '<option  value="'+o.branch_name+'" data-themeid="'+o.branch_name+'">'+o.branch_name+'</option>';
			});

			$('#branch').html(toAppend);

		}else{
			alert("No branch found");
		}
	}
});
});


$("#maintenance_type").change(function(){

	var maintenance_type = $('option:selected', this).attr('data-themeid');

	client_id = $("#client_id").val();
	branch = $("#branch").val();

	console.log(client_id);
	console.log(branch);

	console.log(maintenance_type);

	$.ajax({
		type: "GET",
		url: "{{ route('getcomplaintoramc-daily-service-report.ajax') }}",
		data: {
			'maintenance_type': maintenance_type,
			'client_id': client_id,
			'branch': branch

		},

		success: function(response) {
			
			if(response) {   
				if (maintenance_type == 1) {
				var toAppendProduct = '';
				
				toAppendProduct +='<option value="">All Products</option>';
				$.each(response.assigned_products, function(i,o){

					console.log(o.product.name);

					toAppendProduct += '<option  value="'+o.product.id+'" data-themeid="'+o.product.id+'">'+o.product.name+'</option>';
				});

				$('#product_id').html(toAppendProduct);

					$('#maintenance_type_detail_complaint').show(function(){
						$(this).find("input, select, textarea").prop("disabled", false);
					});
					$('#maintenance_type_detail_amc').hide(function(){
						$(this).find("input, select, textarea").prop("disabled", true);
					});
					$('#complaint_id').prop("required",true);
					$('#complaint_id').prop("disabled",false);
				$('#product_id').prop("required",true);

				}else if(maintenance_type == 2){

					var toAppendProductAmc = '';
					toAppendProductAmc +='<option value="">All Products</option>';

					$.each(response.amc_products, function(i,o){

					console.log(o.product.name);

					toAppendProductAmc += '<option  value="'+o.product_id+'" data-themeid="'+o.product_id+'">'+o.product.name+'</option>';
				});

				$('#product_id_preventive').html(toAppendProductAmc);
					
					$('#maintenance_type_detail_complaint').hide(function(){
						$(this).find("input, select, textarea").prop("disabled", true);
					});
					$('#maintenance_type_detail_amc').show(function(){
						$(this).find("input, select, textarea").prop("disabled", false);
					});
					$('#complaint_id_amc').prop("required",false);
					$('#complaint_id_amc').prop("disabled",true);
					$('#hide_complaint_id_amc').hide();
					$('#product_id_preventive').prop("required",true);
			}
				}else{
					alert('no maintenance type selected');
					$('#maintenance_type_detail_complaint').hide();
					$('#maintenance_type_detail_amc').hide();
					$('#complaint_id').prop("required",false);
					$('#complaint_id').prop("disabled",true);
					$('#product_id').prop("required",false);
					$('#product_id_preventive').prop("required",false);
				}
			}
		});
});

$("#branch").change(function(){

	var branch = $('option:selected', this).attr('data-themeid');

	client_id = $("#client_id").val();
	maintenance_type = $("#maintenance_type").val();


	$.ajax({
		type: "GET",
		url: "{{ route('get-client-contact-person-details.ajax') }}",
		data: {
			'branch': branch,
			'client_id': client_id,
			'maintenance_type': maintenance_type
		},

		success: function(response) {

			

			if(response) {

				console.log(response.amc_products);

				var toAppend = '';

				toAppend +='<tr><th></th><th>Contact Person name</th><th>Contact Person phone no</th></tr>';
				$.each(response.c_person_details, function(i,o){

					contact_person_name = '';
					if (o.contact_person_1_name != null) 
					{
						contact_person_name = o.contact_person_1_name;
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

					toAppend += '<tr><th scope="row"><input type="radio" name="contact_person_details" id="contact_person_1" class="with-gap" value="1" required><label for="contact_person_1"></label></th><td><input type="text" class="form-control" name="contact_person_name" value="'+contact_person_name+'" placeholder="Contact person name" required></td><td><input type="text" class="form-control" name="c_p_1_ph_no" value="'+c_p_1_ph_no+'" placeholder="Contact person phone no" maxlength="10"></td></tr>';

				});

				$('#contact_person_details').html(toAppend);

				$('.auto_hide').show();
			}else{
				alert("No contact person found");
			}
		}
	});
});

$("#product_id").change(function(){

	var product_id = $('option:selected', this).attr('data-themeid');

	$.ajax({
		type: "GET",
		url: "{{ route('get-dsr-product-details.ajax') }}",
		data: {
			'product_id': product_id
		},

		success: function(response) {
			if(response) {
				$('#model_no').val(response.model_no);
				$('#serial_no').val(response.serial_no);

			}else{
				alert("No product details found");
			}
		}
	});
});

$("#product_id_preventive").change(function(){

	var product_id = $('option:selected', this).attr('data-themeid');

	$.ajax({
		type: "GET",
		url: "{{ route('get-dsr-product-details.ajax') }}",
		data: {
			'product_id': product_id
		},

		success: function(response) {
			if(response) {
				$('#model_no_amc').val(response.model_no);
				$('#serial_no_amc').val(response.serial_no);

			}else{
				alert("No product details found");
			}
		}
	});
});

// $(".sp_check_duplicate").change(function(){
setDescriptionValue = function(Obj){
	global_current_spare_part = $(Obj);

	var spare_part_id = $('option:selected', Obj).attr('data-themeid');
	// alert(spare_part_id);

	$.ajax({
		type: "GET",
		url: "{{ route('get-spare-part-details.ajax') }}",
		data: {
			'spare_part_id': spare_part_id
		},

		success: function(response) {
			if(response) {
	// $('#spare_part_description').val(response.name);
	global_current_spare_part.parents("tr").find("#spare_part_description").val(response.name);


}else{
	alert("No product details found");
}
}
});
};

$("#complaint_id").change(function(){

	var complaint_id = $('option:selected', this).attr('data-themeid');

	$.ajax({
		type: "GET",
		url: "{{ route('get-maintenance-complaint-details.ajax') }}",
		data: {
			'complaint_id': complaint_id
		},

		success: function(response) {
			if(response) {
				var x = response.complaint_entry_date.split(" ");
				var y = x[0].split("-");

				$('#call_receive_date').val(y[2]+'-'+y[1]+'-'+y[0]);
				$('#nature_of_complaint_by_customer').val(response.complaint_details);

			}else{
				alert("No complaint details found");
			}
		}
	});
});

$("#complaint_id_amc").change(function(){

	var complaint_id = $('option:selected', this).attr('data-themeid');

	$.ajax({
		type: "GET",
		url: "{{ route('get-maintenance-complaint-details.ajax') }}",
		data: {
			'complaint_id': complaint_id
		},

		success: function(response) {
			if(response) {
				var x = response.complaint_entry_date.split(" ");
				var y = x[0].split("-");

// $('#call_receive_date').val(y[2]+'-'+y[1]+'-'+y[0]);
$('#call_receive_date_amc').val(y[2]+'-'+y[1]+'-'+y[0]);
// $('#nature_of_complaint_by_customer').val(response.complaint_details);
$('#nature_of_complaint_by_customer_amc').val(response.complaint_details);

}else{
	alert("No complaint details found");
}
}
});
});

getDescription = function(Obj){
// var $currentOb = $(Obj);
// $(Obj).val();
// var $currentRow = $currentOb.parents("tr");
// $currentRow.find("#description").html($currentOb.val());
// $currentRow.find("#qnty").html($currentOb.val());
}

checkOnluOne = function(Obj){
	var $current = $(Obj);
	if($current.is(":checked")){

		var $currentRow = $current.parents("td").find("input[type='checkbox']").not($current).prop("checked",false);
	}
} 

isDuplicateAlReadySelected = function(Obj){
// console.log('running function');
var duplicate_value_found = false;
var $current = $(Obj);
if ($current.val()== "") {
	$current.parents("tr").find("input[type='text']").val("");
	$current.parents("tr").find("input[type='checkbox']").prop("checked",false);
	return true;
}else{
	$(".sp_check_duplicate").not($current).each(function(index,element)
	{
		if($(element).val()== $current.val()){
			$current.val("");
			alert('Duplicate value found');
			// $current.parents("tr").find("#spare_part_description").val("");
			$current.parents("tr").find("input[type='text']").val("");
			$current.parents("tr").find("input[type='checkbox']").prop("checked",false);
			duplicate_value_found = true;
			return false;
		}
	});
	if (duplicate_value_found) {
		return false;
	}

	setDescriptionValue(Obj);
}
}
checkQuantity = function(Obj){
	$obj = $(Obj).parents("tr");
	var spare_part_quantity = $obj.find("#spare_part_quantity").val();
	var unit_price_free = $obj.find("#unit_price_free").val();
	var unit_price_chargeable = $obj.find("#unit_price_chargeable").val();

	spare_part_quantity = parseInt(spare_part_quantity);
	if (isNaN(spare_part_quantity)) {
		spare_part_quantity = 0;
		$obj.find("#spare_part_quantity").val(0);
	}
	
	unit_price_free = parseInt(unit_price_free);
	if (isNaN(unit_price_free)) {
		unit_price_free = 0;
		$obj.find("#unit_price_free").val(0);
	}

	unit_price_chargeable = parseInt(unit_price_chargeable);
	if (isNaN(unit_price_chargeable)) {
		unit_price_chargeable = 0;
		$obj.find("#unit_price_chargeable").val(0);
	}

	if(spare_part_quantity < (unit_price_free + unit_price_chargeable)){
		alert("unit price must be equal or less to spare part quantity");
		$(Obj).val(0);
		return false;
	}
}
</script>
@stop

