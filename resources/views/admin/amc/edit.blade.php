@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
	.form-group .form-line .form-label {
		top: -10px!important;
	}
	.Zebra_DatePicker_Icon_Wrapper {
		width: 100%!important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Edit Client for AMC</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view client-amc'))
					<li><a href="{{ route('view-all-client-amc') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-client-amc',Crypt::encrypt($amc_detail->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="zone_id" id="zone_id" required>
		                                <option value=""> Please select a zone </option>
		                                <?php foreach ($zones as $zones): ?>
					                    <option value="{{ $zones->id }}" data-themeid="{{ $zones->id }}" {{ old('zone_id',$amc_detail->client->zone->id) == "$zones->id" ? 'selected' : '' }}>{{ ucwords($zones->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Zone</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control" name="client_id" id="client_id">
										<option value="">-- Please select client --</option>
										@foreach ($all_clients as $client)
						                    @if($client->id==old('client_id',$amc_detail->client_id))
						                    	<option value="{{ $client->name }}" {{ (old('client_id',$amc_detail->client->name) == $client->name) ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
						                    @endif
					                    @endforeach
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

										@foreach ($all_clients as $cp)
						                    @if($cp->id==old('client_id',$amc_detail->client_id))
						                    	<option value="{{ $cp->branch_name }}" {{ (old('branch',$amc_detail->client->branch_name) == $cp->branch_name) ? 'selected' : '' }}>{{ ucwords($cp->branch_name) }}</option>
						                    @endif
					                    @endforeach

									</select>
									<label class="form-label">Select branch</label>
								</div>
							</div>
						</div>

						
					</div>



				<div class="row" id="product_details_auto_hide">
						<div class="col-md-12">
							<div >
								

						<div class="body table-responsive no_data">

							<div class="auto_hide">
									<input type="checkbox" name="select_all" id="select_all" class="filled-in chk-col-cyan" />
									<label for="select_all">Select All</label>
							</div>

                            <table class="table" id="p_details">
                            	
                                <thead>
                                    <tr>
                                    	<th>Product name</th>
                                    	<th>Product code</th>
                                    	<th>Product model no</th>
                                    	<th>Product brand</th>
                                    	<th>Product serial code</th>
                                    	<th>Date of install</th>
                                    </tr>

                                </thead>
                                <tbody>
									@php $i = 1 @endphp
                                	@foreach($client_amc_product as $amc_product)
                                   
                                   <tr>
                                   	<th scope="row"><input type="checkbox" id="product_detail{{ $i }}" name="product_detail[]" class="chk-col-cyan product_detail" value="{{ $amc_product->product->id }}" aria-required="true" checked /><label for="product_detail{{ $i }}">{{ ucwords($amc_product->product->name) }}</label>
                                   	</th>
                                   	<td>{{$amc_product->product->brand}}</td>
                                   	<td>{{$amc_product->product->product_code}}</td>
                                   	<td>{{$amc_product->product->model_no}}</td>
                                   	<td>{{$amc_product->product->serial_no}}</td>
                                   	<td>
                                   		@if($amc_product->product->date_of_purchase != '0000-00-00')
                                   			{{$amc_product->product->date_of_purchase}}
                                   		@endif
                                   </td>
                                   </tr>

                                   @php $i++ @endphp
								   @endforeach
                                   
                                </tbody>
                            </table>
                        </div>



							</div>
						</div>
					</div>




					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="roster_id" id="roster_id" required>
		                                <option value=""> Please select AMC type </option>
		                                <?php foreach ($rosters as $roster): ?>
					                    <option value="{{ $roster->id }}" data-themeid="{{ $roster->id }}" {{ old('roster_id',$amc_detail->roster_id) == "$roster->id" ? 'selected' : '' }}>{{ ucwords($roster->roster_name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select AMC type</label>
									
								</div>
							</div>
						</div>
	
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="amc_duration" id="amc_duration" required>
		                                <option value=""> Please select duration</option>

						                @if($amc_detail->amc_duration==old('amc_duration',$amc_detail->amc_duration))
						                    
						                    @if($amc_detail->roster_id == 1)
						                    	<option value="1" data-themeid='1' {{ (old('amc_duration',$amc_detail->amc_duration) == 1) ? 'selected' : '' }}>1 Month</option>

						                    	<option value="2" data-themeid='2' {{ (old('amc_duration',$amc_detail->amc_duration) == 2) ? 'selected' : '' }}>2 Months</option>

						                    	<option value="3" data-themeid='3' {{ (old('amc_duration',$amc_detail->amc_duration) == 3) ? 'selected' : '' }}>3 Months</option>

						                    	<option value="4" data-themeid='4' {{ (old('amc_duration',$amc_detail->amc_duration) == 4) ? 'selected' : '' }}>4 Months</option>

						                    	<option value="5" data-themeid='5' {{ (old('amc_duration',$amc_detail->amc_duration) == 5) ? 'selected' : '' }}>5 Months</option>

						                    	<option value="6" data-themeid='6' {{ (old('amc_duration',$amc_detail->amc_duration) == 6) ? 'selected' : '' }}>6 Months</option>

						                    	<option value="7" data-themeid='7' {{ (old('amc_duration',$amc_detail->amc_duration) == 7) ? 'selected' : '' }}>7 Months</option>

						                    	<option value="8" data-themeid='8' {{ (old('amc_duration',$amc_detail->amc_duration) == 8) ? 'selected' : '' }}>8 Months</option>

						                    	<option value="9" data-themeid='9' {{ (old('amc_duration',$amc_detail->amc_duration) == 9) ? 'selected' : '' }}>9 Months</option>

						                    	<option value="10" data-themeid='10' {{ (old('amc_duration',$amc_detail->amc_duration) == 10) ? 'selected' : '' }}>10 Months</option>

						                    	<option value="11" data-themeid='11' {{ (old('amc_duration',$amc_detail->amc_duration) == 11) ? 'selected' : '' }}>11 Month</option>

						                    	<option value="12" data-themeid='12' {{ (old('amc_duration',$amc_detail->amc_duration) == 12) ? 'selected' : '' }}>1 Year</option>

						                    	<option value="13" data-themeid='13' {{ (old('amc_duration',$amc_detail->amc_duration) == 13) ? 'selected' : '' }}>1 Year 1 Month</option>

						                    	<option value="14" data-themeid='14' {{ (old('amc_duration',$amc_detail->amc_duration) == 14) ? 'selected' : '' }}>1 Year and 2 Months</option>

						                    	<option value="15" data-themeid='15' {{ (old('amc_duration',$amc_detail->amc_duration) == 15) ? 'selected' : '' }}>1 Year and 3 Months</option>

						                    	<option value="16" data-themeid='16' {{ (old('amc_duration',$amc_detail->amc_duration) == 16) ? 'selected' : '' }}>1 Year and 4 Months</option>

						                    	<option value="17" data-themeid='17' {{ (old('amc_duration',$amc_detail->amc_duration) == 17) ? 'selected' : '' }}>1 Year and 5 Months</option>

						                    	<option value="18" data-themeid='18' {{ (old('amc_duration',$amc_detail->amc_duration) == 18) ? 'selected' : '' }}>1 Year and 6 Months</option>

						                    	<option value="19" data-themeid='19' {{ (old('amc_duration',$amc_detail->amc_duration) == 19) ? 'selected' : '' }}>1 Year and 7 Months</option>

						                    	<option value="20" data-themeid='20' {{ (old('amc_duration',$amc_detail->amc_duration) == 20) ? 'selected' : '' }}>1 Year and 8 Months</option>

						                    	<option value="21" data-themeid='21' {{ (old('amc_duration',$amc_detail->amc_duration) == 21) ? 'selected' : '' }}>1 Year and 9 Months</option>

						                    	<option value="22" data-themeid='22' {{ (old('amc_duration',$amc_detail->amc_duration) == 22) ? 'selected' : '' }}>1 Year and 10 Months</option>

						                    	<option value="23" data-themeid='23' {{ (old('amc_duration',$amc_detail->amc_duration) == 23) ? 'selected' : '' }}>1 Year and 11 Months</option>

						                    	<option value="24" data-themeid='24' {{ (old('amc_duration',$amc_detail->amc_duration) == 24) ? 'selected' : '' }}>2 Years</option>
						                    @endif

						                    @if($amc_detail->roster_id == 2)
						                    	
						                    	<option value="3" data-themeid='3' {{ (old('amc_duration',$amc_detail->amc_duration) == 3) ? 'selected' : '' }}>3 Months</option>

						                    	<option value="6" data-themeid='6' {{ (old('amc_duration',$amc_detail->amc_duration) == 6) ? 'selected' : '' }}>6 Months</option>

						                    	<option value="9" data-themeid='9' {{ (old('amc_duration',$amc_detail->amc_duration) == 9) ? 'selected' : '' }}>9 Months</option>

						                    	<option value="12" data-themeid='12' {{ (old('amc_duration',$amc_detail->amc_duration) == 12) ? 'selected' : '' }}>1 Year</option>

						                    	<option value="15" data-themeid='15' {{ (old('amc_duration',$amc_detail->amc_duration) == 15) ? 'selected' : '' }}>1 Year and 3 Months</option>

						                    	<option value="18" data-themeid='18' {{ (old('amc_duration',$amc_detail->amc_duration) == 18) ? 'selected' : '' }}>1 Year and 6 Months</option>

						                    	<option value="21" data-themeid='21' {{ (old('amc_duration',$amc_detail->amc_duration) == 21) ? 'selected' : '' }}>1 Year and 9 Months</option>

						                    	<option value="24" data-themeid='24' {{ (old('amc_duration',$amc_detail->amc_duration) == 24) ? 'selected' : '' }}>2 Years</option>
						                    @endif

						                    @if($amc_detail->roster_id == 3)
						                    	<option value="6" data-themeid='6' {{ (old('amc_duration',$amc_detail->amc_duration) == 6) ? 'selected' : '' }}>6 Months</option>
	
						                    	<option value="12" data-themeid='12' {{ (old('amc_duration',$amc_detail->amc_duration) == 12) ? 'selected' : '' }}>1 Year</option>

						                    	<option value="18" data-themeid='18' {{ (old('amc_duration',$amc_detail->amc_duration) == 18) ? 'selected' : '' }}>1 Year and 6 Months</option>
	
						                    	<option value="24" data-themeid='24' {{ (old('amc_duration',$amc_detail->amc_duration) == 24) ? 'selected' : '' }}>2 Years</option>
						                    @endif

						                    @if($amc_detail->roster_id == 4)
						                    	<option value="12" data-themeid='12' {{ (old('amc_duration',$amc_detail->amc_duration) == 12) ? 'selected' : '' }}>1 Year</option>

						                    	<option value="24" data-themeid='24' {{ (old('amc_duration',$amc_detail->amc_duration) == 24) ? 'selected' : '' }}>2 Years</option>
						                    @endif

						                @endif
					                   
		                            </select>
									<label class="form-label">Select duration</label>
									
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker_month_only" id="amc_start_date" name="amc_start_date" placeholder="AMC start date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('amc_start_date',dateFormat($amc_detail->amc_start_date)) }}" autocomplete="off" required="required">
									<label class="form-label">AMC start date</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="amc_demand" name="amc_demand" placeholder="AMC demand total amount" value="{{ old('amc_demand',$amc_detail->amc_amount) }}" min="0" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" maxlength="10" autocomplete="off" required="required">
									<label class="form-label">AMC demand total amount</label>
								</div>
							</div>
						</div>
		
					</div>
		 
				<div id="main_parent">
					@if(count($amc_transaction_details)>0)
						
						<div class="row" class="sub_sp_parent">
							<div class="col-md-12">
								<h4>Add AMC details</h4><hr>
								<div class="body table-responsive">
									<table class="table" id="main_sp_parent">

										<thead>
											<tr>
												<th>#</th>
												<th>AMC request date</th>
												<th>Collected amount</th>
												<th>Amount collected date</th>
												<th>Remarks</th>

											</tr>
										</thead>

										<tbody>
											@php $i = 1; @endphp
											@foreach($amc_transaction_details as $amc_transaction_detail)
											<tr>

												<td>{{ $i }}</td>
												<td>
													<span id="date_only"><div class="form-group form-float" ><div class="form-line"><input type="text" class="form-control datepicker" id="amc_rqst_date" name="amc_rqst_date[]" placeholder="AMC request date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('amc_rqst_date',date('d-m-Y', strtotime($amc_transaction_detail->amc_rqst_date))) }}" required="required"></div></div></span>
												</td>
												<td>
													<div class="form-group form-float">
														<div class="form-line">
															<input type="text" class="form-control" name="amc_demand_collected[]" placeholder="Collected amount" value="{{ $amc_transaction_detail->amc_demand_collected }}">
														</div>
													</div>

												</td>
												<td>
													<div class="form-group form-float">
														<div class="form-line">
															<input type="text" class="form-control datepicker" id="datepicker" name="amc_demand_collected_date[]" placeholder="Amount collected date" value="{{ $amc_transaction_detail->amc_demand_collected_date }}">
														</div>
													</div>
												</td>
												<td>
													<div class="form-group form-float">
														<div class="form-line">
															<input type="text" class="form-control" name="remarks[]" placeholder="Remarks" value="{{ $amc_transaction_detail->remarks }}">
														</div>
													</div>

												</td>


											</tr>
											@php $i++; @endphp
											@endforeach

										</tbody>
									</table>
								</div>
							</div>
						</div>
					@endif
				</div>
				
				<button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>




@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
 <script>

 $('.datepicker').Zebra_DatePicker({
      	format: 'd-m-Y',
      	// direction: false
      	
  });
 $('.datepicker_month_only').Zebra_DatePicker({
      	format: 'd-m-Y',
      	// direction: false
      	onChange: function(view, elements) {
	 		console.log(elements);
	        // on the "days" view...
	        if (view == 'days') {
	        }
	    },
	    onSelect: function() {
	        changeDateAsRoster();
	    }
  });
 $(".datepicker_month_only").change(function(){
	alert('hiiiii');
 });

 


$('#amc_details').hide();


$('#select_all').click(function() {
	var checkboxes = $('.product_detail');
	// alert($(this).is(':checked'));
	if($(this).is(':checked')) {
		checkboxes.prop("checked" , true);
	} else {
		checkboxes.prop ( "checked" , false );
	}
});

// $(document).ready(function(){
@if (!$client_amc_product) 
 {
 	$('.auto_hide').hide();
 	$('#product_details_auto_hide').hide();
 	 document.getElementByClass("product_detail").checked = true;
 }
 @endif
// });


@if ($client_amc_product) 
 {
	$('.auto_hide').show();
 	$('#product_details_auto_hide').show();
 	document.getElementsByClassName("product_detail")[0].checked = true;
 }
 @endif


 $("#zone_id").change(function(){
  
  var zone_id = $('option:selected', this).attr('data-themeid');

   
  $.ajax({
    type: "GET",
    url: "{{ route('zone-wise-client-details.ajax') }}",
    data: {
      'zone_id': zone_id
    },

    success: function(response) {
      if(response) {

        var toAppend = '';

        toAppend +='<option value="">All clients</option>';
        $.each(response, function(i,o){

        	// console.log(o.name);
   

         toAppend+= '<option  value="'+o.name+'" {{ old('client_id') == "'+o.name+'" ? 'selected' : '' }} data-themeid="'+o.name+'">'+o.name+'</option>';
        });

        $('#client_id').html(toAppend);
        
      }else{
        alert("No subgroup found");
      }
    }
  });
  });

 $("#client_id").change(function(){

	var client_id = $('option:selected', this).attr('data-themeid');


$.ajax({
	type: "GET",
	url: "{{ route('client-wise-branch-details.ajax') }}",
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

	var client_id = $("#client_id").val();
	var branch = $("#branch").val();

	// alert(branch);


$.ajax({
	type: "GET",
	url: "{{ route('assigned-product-details.ajax') }}",
	data: {
		'client_id': client_id,
		'branch': branch
	},

	success: function(response) {
		if(response) {

			// alert(response);
			// console.log(response);

			var toAppend = '';

			toAppend +='<tr><th>Product name</th><th>Product code</th><th>Product model no</th><th>Product brand</th><th>Product serial code</th><th>Date of install</th></tr>';

			$.each(response, function(i,o){

			// console.log(o.product.name);
			date_of_purchase = '';
          if (o.product.date_of_purchase != '0000-00-00') 
          {
            date_of_purchase = o.product.date_of_purchase;
          }

          toAppend += '<tr><th scope="row"><input type="checkbox" id="product_detail'+o.product.id+'" name="product_detail[]" class="chk-col-cyan product_detail" value="'+o.product.id+'" aria-required="true" checked /><label for="product_detail'+o.product.id+'">'+o.product.name+'</label></th><td>'+o.product.brand+'</td><td>'+o.product.product_code+'</td><td>'+o.product.model_no+'</td><td>'+o.product.serial_no+'</td><td>'+date_of_purchase+'</td></tr>'
        });

        $('#p_details').html(toAppend);
        $('.auto_hide').show();
        $('#product_details_auto_hide').show();

        // document.getElementByClass("product_detail").checked = true;

        $('#p_details').html(toAppend).find('input[id^=datepicker]').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false
	    });

		}else{
			alert("No product found");

			 toAppend +='<p>No product found for this client</p>';
	          $('#p_details').html(toAppend);
	 
	          $(".product_detail").prop("checked", false);
	          $('.auto_hide').hide();
	          $('#product_details_auto_hide').hide();
		}
	}
});
});




$('#roster_id').change(function(){
	
	console.log("changed event roster_id");
	$('#amc_duration').html('<option value="">Please select duration</option>');
	if($(this).val() == 1){
		$('#amc_duration').append("<option value='1 ' data-themeid='1'>1 Month</option><option value='2 ' data-themeid='2'>2 Months</option><option value='3 ' data-themeid='3'>3 Months</option><option value='4 ' data-themeid='4'>4 Months</option><option value='5 ' data-themeid='5'>5 Months</option><option value='6 ' data-themeid='6'>6 Months</option><option value='7 ' data-themeid='7'>7 Months</option><option value='8 ' data-themeid='8'>8 Months</option><option value='9 ' data-themeid='9'>9 Months</option><option value='10 ' data-themeid='10'>10 Months</option><option value='11 ' data-themeid='11'>11 Months</option><option value='12' data-themeid='12'>1 Year</option><option value='13' data-themeid='13'>1 Year and 1 Month</option><option value='14' data-themeid='14'>1 Year and 2 Months</option><option value='15' data-themeid='15'>1 Year and 3 Months</option><option value='16' data-themeid='16'>1 Year and 4 Months</option><option value='17' data-themeid='17'>1 Year and 5 Months</option><option value='18' data-themeid='18'>1 Year and 6 Months</option><option value='19' data-themeid='19'>1 Year and 7 Months</option><option value='20' data-themeid='20'>1 Year and 8 Months</option><option value='21' data-themeid='21'>1 Year and 9 Months</option><option value='22' data-themeid='22'>1 Year and 10 Months</option><option value='23' data-themeid='23'>1 Year 11 Months</option><option value='24' data-themeid='24'>2 Years</option>");
	}
	else if($(this).val() == 2){
		$('#amc_duration').append("<option  value='3' data-themeid='3'>3 Month</option><option  value='6' data-themeid='6'>6 Month</option><option  value='9' data-themeid='9'>9 Month</option><option  value='12' data-themeid='12'>1 Year</option><option  value='15' data-themeid='15'>1 Year and 3 Months</option><option  value='18' data-themeid='18'>1 Year and 6 Months</option><option  value='21' data-themeid='21'>1 Year and 9 Months</option><option  value='24' data-themeid='24'>2 Years</option>");
	}
	else if($(this).val() == 3){
		$('#amc_duration').append("<option value='6' data-themeid='6'>6 Month</option><option value='12' data-themeid='12'>1 Year</option><option value='18' data-themeid='18'>1 Year and 6 Months</option><option value='24' data-themeid='24'>2 Years</option>");
	}else if($(this).val() == 4){
		$('#amc_duration').append("<option value='12' data-themeid='12'>1 Year</option><option value='24' data-themeid='24'>2 Years</option>");
	}
	$("#main_parent").html("");
});
$("#amc_duration").change(function(){
	
	console.log("changed event amc_duration");
	var roster_value = $("#roster_id").val();
	var current_value = $(this).val();
	console.log(roster_value);
	console.log(current_value);
	if (current_value.trim() == "") {
		$("#main_parent").html("");
		return false;
	}
	if (roster_value.trim() == "") {
		$("#main_parent").html("");
		return false;
	}
	var row_to_repeat = 0;
	if(roster_value == 1){
		row_to_repeat = current_value;
	}else if(roster_value == 2){
		row_to_repeat = current_value/3;
	}else if(roster_value == 3){
		row_to_repeat = current_value/6;
	}else if(roster_value == 4){
		row_to_repeat = current_value/12;
	}

	// alert(row_to_repeat);

	html = "";
	html +='<div class="row" class="sub_sp_parent">';
			html +='<div class="col-md-12">';
				html +='<h4>Add AMC details</h4><hr>';
				html +='<div class="body table-responsive">';
					html +='<table class="table" id="main_sp_parent">';
                    	
                        html +='<thead>';
                            html +='<tr>';
                            	html +='<th>#</th>';
                            	html +='<th>AMC request date</th>';
                            	// html +='<th>Demand Amount</th>';
                            	html +='<th>Collected amount</th>';
                            	html +='<th>Amount collected date</th>';
                            	html +='<th>Remarks</th>';
                            	
                            html +='</tr>';
                        html +='</thead>';

                        html +='<tbody>';
                       
							for (var i =  0; i < row_to_repeat; i++) {
								html +='<tr>';
                           	
                           	html +='<td>'+ (i+1)+'.</td>';
                           	html +='<td><span id="date_only"><div class="form-group form-float" ><div class="form-line"><input type="text" class="form-control datepicker" id="amc_rqst_date" name="amc_rqst_date[]" placeholder="AMC request date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="NA" required="required"></div></div></span></td>';
                           	// html +='<td>';
                           	// 	html +='<div class="form-group form-float">';
	                           //      html +='<div class="form-line">';
	                           //         html +='<input type="text" class="form-control" name="amc_demand[]" required="required" placeholder="Demand Amount">';
	                           //     html +='</div>';
	                           // html +='</div>';

                           	// html +='</td>';
                           	html +='<td>';
                           		html +='<div class="form-group form-float">';
	                                html +='<div class="form-line">';
	                                   html +='<input type="text" class="form-control" id="amc_demand_collected'+ (i+1)+'" name="amc_demand_collected[]" placeholder="Collected amount">';
	                                html +='</div>';
	                           html +='</div>';

                            html +='</td>';
                           	html +='<td>';
                           		html +='<div class="form-group form-float">';
	                                html +='<div class="form-line">';
	                                   html +='<input type="text" class="form-control datepicker" id="datepicker'+ (i+1)+'" name="amc_demand_collected_date[]" placeholder="Amount collected date">';
	                               html +='</div>';
	                           html +='</div>';
						  	html +='</td>';
                           	html +='<td>';
                           		html +='<div class="form-group form-float">';
	                                html +='<div class="form-line">';
	                                   html +='<input type="text" class="form-control" name="remarks[]" placeholder="Remarks">';
	                               html +='</div>';
	                           html +='</div>';

                           	html +='</td>';
                           	

                           html +='</tr>';
							}

					      html +='</tbody>';
                    html +='</table>';
                html +='</div>';
			html +='</div>';
		html +='</div>';
	html +='</div>';
	$("#main_parent").html(html);
	$('#main_parent').find('.datepicker').Zebra_DatePicker({
      	format: 'd-m-Y',
      	direction: false
    });
    changeDateAsRoster();
});

changeDateAsRoster = function(){
	console.log("calling changeDateAsRoster");
	var amc_date = $("#amc_start_date").val();
	var roster_value = $("#roster_id").val();
	var montwise_value = $("#amc_duration").val();
	if(amc_date == ""){
		console.log("amc date not found.");
		return false;
	}
	if (roster_value == "") {
		console.log("roster value not found.");
		return false;
	}
	if (montwise_value == "") {
		console.log("month value not found.");
		return false;
	}
	var all_repeated_row = $(document).find("#main_sp_parent").find("tr");

	var row_to_repeat = 0;
	var month_addition = 0;
	if(roster_value == 1){
		row_to_repeat = montwise_value;
		month_addition = 1;
	}else if(roster_value == 2){
		row_to_repeat = montwise_value/3;
		month_addition = 3;
	}else if(roster_value == 3){
		row_to_repeat = montwise_value/6;
		month_addition = 6;
	}else if(roster_value == 4){
		row_to_repeat = montwise_value/12;
		month_addition = 12;
	}
	var splited_amc_date = amc_date.split("-");
	var newDate = new Date(parseInt(splited_amc_date[2]), (parseInt(splited_amc_date[1])-1), parseInt(splited_amc_date[0]));
	all_repeated_row.each(function(index, element){
		var show_date = "";
		var dd ="";
		var mm ="";
		var y = "";
		if(index == 0){
			dd = ("0"+newDate.getDate()).slice(-2);
			mm = ("0"+(newDate.getMonth() + 1)).slice(-2);
			y = newDate.getFullYear();
			show_date = dd+"-"+mm+"-"+y;
			$(element).find("#date_only").html(show_date);
			$(element).find("#hidden_date").val(show_date);
		}else{
			dd = ("0"+newDate.getDate()).slice(-2);
			mm = ("0"+(newDate.getMonth() + 1)).slice(-2);
			y = newDate.getFullYear();
			newDate.setMonth(newDate.getMonth() + month_addition);
			show_date = dd+"-"+mm+"-"+y;
			// $(element).find("#date_only").html(show_date);
			$(element).find("#amc_rqst_date").val(show_date);
		}
	});
}
 </script>
@stop

