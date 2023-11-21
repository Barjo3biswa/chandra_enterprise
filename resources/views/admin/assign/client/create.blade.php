@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
	/*.auto_hide {
		display: none;
	}*/
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Assign Product To Client</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view assign-product-to-client'))
					<li><a href="{{ route('view-all-assign-client') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('assign-new-product-to-client.post') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-3">
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

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="branch" id="branch" required>
		                                <option value=""> Please select a branch </option>
		                               
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="company_id" id="company_id" required>
		                                <option value=""> Please select a company </option>
		                                <?php foreach ($companies as $company): ?>
					                    <option value="{{ $company->id }}" data-themeid="{{ $company->id }}" {{ old('company_id') == "$company->id" ? 'selected' : '' }}>{{ ucwords($company->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>
					


						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="group_id" id="group_id" required>
		                                <option value=""> Please select a group </option>
		                                <!--<?php foreach ($groups as $group): ?>
					                    <option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
					                    <?php endforeach; ?>-->
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="product_id" id="product_id" required>
		                                <option value=""> Please select a Product </option>	    
		                            </select>
								</div>
							</div>
						</div>

					</div>

						<!--<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="sub_group_id" id="sub_group_id" required>
		                                <option value=""> Please select a sub group </option>
		                                
		                            </select>
								</div>
							</div>
						</div>-->
					
					<div id="error_msg">
			            <p class="text-danger">You cannot submit data as there is no product to select</p>
			        </div>
					
					<div class="row">
						<div class="col-md-12">
							<div >
								

						<div class="body table-responsive no_data">

							<div class="auto_hide">
									<input type="checkbox" name="select_all" id="select_all" class="filled-in chk-col-cyan" />
									<label for="select_all">Select All</label>
							</div>

                            <table class="table" id="p_details">
                            	
                                <thead>
                                    
                                </thead>
                                <tbody>
                                   
                                   
								
                                   
                                </tbody>
                            </table>
                        </div>



							</div>
						</div>
					</div>

					<button class="btn btn-primary waves-effect" id="cnt_submt" onclick="return confirm('Are you sure')" type="submit">SUBMIT</button>
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
      direction: false
  });

 // $("#group_id").change(function(){
  
 //  var group_id = $('option:selected', this).attr('data-themeid');

   
 //  $.ajax({
 //    type: "GET",
 //    url: "{{ route('get-sub-group.ajax') }}",
 //    data: {
 //      'group_id': group_id
 //    },

 //    success: function(response) {
 //      if(response) {

 //        var toAppend = '';

 //        toAppend +='<option value="">All Sub Groups</option>';
 //        $.each(response, function(i,o){

 //        	console.log(o);
 //          toAppend += '<option  value="'+o.name+'" data-themeid="'+o.id+'">'+o.name+'</option>';
 //        });

 //        $('#sub_group_id').html(toAppend);

 //      }else{
 //        alert("No subgroup found");
 //      }
 //    }
 //  });
 //  });

$(document).ready(function(){
 	$('.auto_hide').hide();
 	$('#error_msg').hide();
 	$('#cnt_submt').hide();
 	$("#cnt_submt").prop("disabled", true);
 	 document.getElementByClass("product_detail").checked = true;
});
// alert(document.getElementByClass("product_detail"));



 $('#select_all').click(function() {
	var checkboxes = $('.product_detail');
	// alert($(this).is(':checked'));
	if($(this).is(':checked')) {
		checkboxes.prop("checked" , true);
	} else {
		checkboxes.prop ( "checked" , false );
	}
});

 $("#client_id").change(function(){
  
  var client_id = $('option:selected', this).attr('data-themeid');

  // alert(client_id);

   
  $.ajax({
    type: "GET",
    url: "{{ route('get-client-branch-details.ajax') }}",
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


 $("#company_id").change(function(){
  
  var company_id = $('option:selected', this).attr('data-themeid');

   
  $.ajax({
    type: "GET",
    url: "{{ route('get-group.ajax') }}",
    data: {
      'company_id': company_id
    },

    success: function(response) {
      if(response) {
      	$('.no_data').show();

        var toAppend = '';

        toAppend +='<option value="">All Product Groups</option>';
        $.each(response, function(i,o){

        	console.log(o.name);

          toAppend += '<option  value="'+o.id+'" data-themeid="'+o.id+'">'+o.name+'</option>';
        });

        $('#group_id').html(toAppend);

      }else{
        alert("No product group found");
        toAppend +='<option value="">All Product Groups</option>';
        $('#group_id').html(toAppend);
 
        $(".product_detail").prop("checked", false);
        $('.no_data').hide();

	  }
    }
  });
  });

var response_data ='';
 $("#group_id").change(function(){ 
  var group_id = $('option:selected', this).attr('data-themeid');
  var e = document.getElementById("company_id");
  var company_id = e.options[e.selectedIndex].value;
  // alert(company_id);
  $('#p_details').empty();
  $("#product_id").empty();
  $.ajax({
    type: "GET",
    url: "{{ route('get-details-assign-new-product-to-client.ajax') }}",
    data: {
      'company_id': company_id,
      'group_id': group_id

    },
    success: function(response) {
      if(response) {
		
		var toAppend = [''];
		$.each(response, function(i,o){		
			toAppend += '<option value="'+i+'">'+o.name+(o.serial_no)+'</option>'
		});
		$("#product_id").append(toAppend);
		response_data = response;
		console.log(response_data);
        // console.log(response.group_id);
				// 	if (!$.trim(response)){ 
				// 		 // alert("is not blank: " + response);
				// 		$('.no_data').hide();
				// 		$('#cnt_submt').hide();
				//         $('#error_msg').fadeIn(1000);
				//         $('#p_details').hide();
				//         $('.auto_hide').hide();
				//         $('#select_all').hide();
				//         $("#cnt_submt").prop("disabled", true);
				//     }else{
				// 	 var toAppend = '';

				//     toAppend +='<tr><th>Product name</th><th>Product code</th><th>Product model no</th><th>Product brand</th><th>Product serial code</th><th>Date of install</th></tr>';
				//     $.each(response, function(i,o){

				//      date_of_purchase = '';
				//       if (o.date_of_purchase != '0000-00-00') 
				//       {
				//         date_of_purchase = o.date_of_purchase;
				//       }

				//       toAppend += '<tr><th scope="row"><input type="checkbox" id="product_detail'+o.id+'" name="product_detail[]" class="chk-col-cyan product_detail" value="'+o.id+'" aria-required="true" checked /><label for="product_detail'+o.id+'">'+o.name+'</label></th><td>'+o.brand+'</td><td>'+o.product_code+'</td><td>'+o.model_no+'</td><td>'+o.serial_no+'</td><td><input type="text" style="width:200px;" name="date_of_install[]" class="form-control datepicker" value="'+date_of_purchase+'" id="datepicker'+o.id+'" placeholder="eg,(dd-mm-yyyy)" data-zdp_readonly_element="false"></td></tr>'
				//     });

				//     $('.no_data').show();
				//     $('#p_details').html(toAppend);
				//     $('.auto_hide').show();
				//     $('#error_msg').fadeOut(1000);
				//     $('#select_all').show();
				//     $('#cnt_submt').show();
				//     $("#cnt_submt").prop("disabled", false);

				//     // document.getElementByClass("product_detail").checked = true;

				//     $('#p_details').html(toAppend).find('input[id^=datepicker]').Zebra_DatePicker({
				//       format: 'd-m-Y',
				//       direction: false
				//     });
				// 	}

				//   }else{
				//     alert("No details found");
       }
    }
  });
  });

  $("#product_id").change(function(){
	var index = $("#product_id").val();
	console.log(response_data[index]);
		if (!$.trim(response)){ 
			$('.no_data').hide();
			$('#cnt_submt').hide();
			$('#error_msg').fadeIn(1000);
			$('#p_details').hide();
			$('.auto_hide').hide();
			$('#select_all').hide();
			$("#cnt_submt").prop("disabled", true);
		}else{
			var toAppend = '';
			toAppend +='<tr><th>Product name</th><th>Product code</th><th>Product model no</th><th>Product brand</th><th>Product serial code</th><th>Date of install</th></tr>';
			date_of_purchase = '';
			if (response_data[index].date_of_purchase != '0000-00-00') 
			{
			date_of_purchase = response_data[index].date_of_purchase;
			}
			toAppend += '<tr><th scope="row"><input type="checkbox" id="product_detail'+response_data[index].id+'" name="product_detail[]" class="chk-col-cyan product_detail" value="'+response_data[index].id+'" aria-required="true" checked /><label for="product_detail'+response_data[index].id+'">'+response_data[index].name+'</label></th><td>'+response_data[index].brand+'</td><td>'+response_data[index].product_code+'</td><td>'+response_data[index].model_no+'</td><td>'+response_data[index].serial_no+'</td><td><input type="text" style="width:200px;" name="date_of_install[]" class="form-control datepicker" value="'+date_of_purchase+'" id="datepicker'+response_data[index].id+'" placeholder="eg,(dd-mm-yyyy)" data-zdp_readonly_element="false"></td></tr>';
			$('.no_data').show();
			$('#p_details').html(toAppend);
			$('.auto_hide').show();
			$('#error_msg').fadeOut(1000);
			$('#select_all').show();
			$('#cnt_submt').show();
			$("#cnt_submt").prop("disabled", false);
			$('#p_details').html(toAppend).find('input[id^=datepicker]').Zebra_DatePicker({
				format: 'd-m-Y',
				direction: false
			});
		}
  })
 </script>
@stop

