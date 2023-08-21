@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
	/*.auto_hide {
		display: none;
	}*/
	.form-group .form-line .form-label
	{
		top: -10px!important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update Product Details Assigned To Client</h2>
				<ul class="header-dropdown m-r--5">
					<li><a href="{{ route('view-all-assign-client') }}" class="btn btn-success">View all</a></li>
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('product-update-assign-to-client',Crypt::encrypt($assign_product->product_id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					<input type="hidden" name="assgn_client_id" value="{{ $assign_client_name->id }}">

					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="client_id" id="client_id" disabled="true">
		                                <option value=""> Please select a client </option>
		                                <?php foreach ($clients as $client): ?>
					                    <option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id',$assign_client_name->name) == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Client</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">

									 <select class="form-control show-tick" name="branch" id="branch" disabled="true">
		                                <option value=""> Please select a branch </option>
		                               @foreach ($clients as $client)
				                        @if($client->branch_name==$assign_client_name->branch_name)
				                          <option value="{{ $client->branch_name }}" {{ (old('branch',$assign_client_name->branch_name) == $client->branch_name) ? 'selected' : '' }}>{{ ucwords($client->branch_name) }}</option>
				                        @endif
				                      @endforeach
		                            </select>
									<label class="form-label">Select Branch</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="company_id" id="company_id" required>
		                                <option value=""> Please select a company </option>
		                                <?php foreach ($companies as $company): ?>
					                    <option value="{{ $company->id }}" data-themeid="{{ $company->id }}" {{ old('company_id',$assign_product->company_id) == "$company->id" ? 'selected' : '' }}>{{ ucwords($company->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Company</label>
								</div>
							</div>
						</div>
					


						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="group_id" id="group_id" required>
		                                <option value=""> Please select a group </option>
		                               <?php foreach ($groups as $group): ?>
					                    <option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id',$assign_product->product->group_id) == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Group</label>
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
					
					
					
					<div class="row" id="p_details1">
						<div class="col-md-12">
					
						<div class="body table-responsive no_data1">
						   <table class="table" >
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
                                   
                                  <tr>
                                   	<td>{{ ucwords($assign_product->product->name) }}</td>
                                   	<td>{{ $assign_product->product->product_code }}</td>
                                   	<td>{{ $assign_product->product->model_no }}</td>
                                   	<td>{{ $assign_product->product->brand }}</td>
                                   	<td>{{ $assign_product->product->serial_no }}</td>
                                   	<td>
                                   		@if($assign_product->product->date_of_install != "0000-00-00")
		                                	{{ date('d M, Y', strtotime($assign_product->product->date_of_install)) }}
		                                @endif
		                            </td>
                                  </tr>
							      
                                </tbody>
                                
                            </table>
                       
							</div>

						</div>
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

	
					<button class="btn btn-primary waves-effect" id="cnt_submt" onclick="return confirm('Are you sure')" type="submit">UPDATE</button>
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


$(document).ready(function(){
 	$('.auto_hide').hide();
 	$('#cnt_submt').hide();
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


 $("#group_id").change(function(){
  
  var group_id = $('option:selected', this).attr('data-themeid');

  var e = document.getElementById("company_id");
  var company_id = e.options[e.selectedIndex].value;
  
  // alert(company_id);

  $.ajax({
    type: "GET",
    url: "{{ route('get-details-assign-new-product-to-client.ajax') }}",
    data: {
      'company_id': company_id,
      'group_id': group_id

    },

    success: function(response) {
      if(response) {

        // console.log(response.group_id);
        if (!$.trim(response)){   
              alert("No product to select");
              $('#cnt_submt').hide();
              $('#p_details1').show();
              $('#p_details').hide();
              $('.auto_hide').hide();

        }else{

        var toAppend = '';

        toAppend +='<tr><th>Product name</th><th>Product code</th><th>Product model no</th><th>Product brand</th><th>Product serial code</th><th>Date of install</th></tr>';
        $.each(response, function(i,o){

         date_of_purchase = '';
          if (o.date_of_purchase != '0000-00-00') 
          {
            date_of_purchase = o.date_of_purchase;
          }

          toAppend += '<tr><th scope="row"><input type="checkbox" id="product_detail'+o.id+'" name="product_detail[]" class="chk-col-cyan product_detail" value="'+o.id+'" aria-required="true" checked /><label for="product_detail'+o.id+'">'+o.name+'</label></th><td>'+o.brand+'</td><td>'+o.product_code+'</td><td>'+o.model_no+'</td><td>'+o.serial_no+'</td><td><input type="text" style="width:200px;" name="date_of_install[]" class="form-control datepicker" value="'+date_of_purchase+'" id="datepicker'+o.id+'" placeholder="eg,(dd-mm-yyyy)" data-zdp_readonly_element="false"></td></tr>'
        });

        $('#p_details').html(toAppend);
        $('.auto_hide').show();
        $('#cnt_submt').show();
        $('#p_details1').hide();

        // document.getElementByClass("product_detail").checked = true;

        $('#p_details').html(toAppend).find('input[id^=datepicker]').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false
	    });

    	}

      }else{
        alert("No details found");
      }
    }
  });
  });
 </script>
@stop

