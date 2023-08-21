@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
    .form-group .form-line .form-label {
        top: -10px!important;
        font-size: 12px!important;
    }
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add New Outstanding</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view client outstanding bill'))
					<li><a href="{{ route('view-all-client-outstanding-bill') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-client-outstanding-bill.store') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="client_id" id="client_id" required>
		                                <option value=""> Please select one client </option>
		                                <?php foreach ($clients as $client): ?>
					                    <option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id') == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Client</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="branch" id="branch" required>
		                                <option value=""> Please select one branch </option>
		                                
		                            </select>
									<label class="form-label">Select Branch</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="company_id" required>
		                                <option value=""> Please select one company </option>
		                                <?php foreach ($companies as $company): ?>
					                    <option value="{{ $company->id }}" {{ old('company_id') == "$company->id" ? 'selected' : '' }}>{{ ucwords($company->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Company</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="group_id" required>
		                                <option value=""> Please select one group </option>
		                                <?php foreach ($groups as $group): ?>
					                    <option value="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select Group</label>
								</div>
							</div>
						</div>
	
					</div>
				
					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="bill_no" value="{{ old('bill_no') }}" required>
									<label class="form-label">Bill No</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="bill_date" value="{{ old('bill_date') }}" required>
									<label class="form-label">Bill Date</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="bill_amount" value="{{ old('bill_amount') }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" required>
									<label class="form-label">Bill Amount</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="pay_by_date" value="{{ old('pay_by_date') }}" required>
									<label class="form-label">Pay By Date</label>
								</div>
							</div>
						</div>
				 	</div>

 				<h4>Add product details</h4><hr><br>
 				<div id="main_sp_parent">
 					<div class="row" id="sub_sp_parent">

 						<div class="col-md-3">
 							<div class="form-group form-float">
 								<div class="form-line">
 									<input type="text" class="form-control" name="product_name[]" value="{{ old('product_name') }}" required>
 									<label class="form-label">Product name</label>
 								</div>
 							</div>
 						</div>

 						<div class="col-md-3">
 							<div class="form-group form-float">
 								<div class="form-line">
 									<input type="text" class="form-control" name="product_quantity[]" value="{{ old('product_quantity') }}" required>
 									<label class="form-label">Product Quantity</label>
 								</div>
 							</div>
 						</div>

 						<div class="col-md-3">
 							<div class="form-group form-float">
 								<div class="form-line">
 									<input type="text" class="form-control" name="product_price[]" value="{{ old('product_price') }}" required>
 									<label class="form-label">Product Price</label>
 								</div>
 							</div>
 						</div>

 						<div class="col-md-3">
 							<div class="btn-group">
 								<button type="button" class="btn btn-success btn-sm add_new_ap" data-toggle="tooltip" title="Add new product"><i class="fa fa-plus"></i></button>

 							</div>
 						</div>

 					</div>
 				</div>

					<button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
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
  // direction: false,
 
});

 $("#client_id").change(function(){
  
  var client_id = $('option:selected', this).attr('data-themeid');

  // alert(client_id);

   
  $.ajax({
    type: "GET",
    url: "{{ route('get-all-client-branch.ajax') }}",
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

 $('#main_sp_parent').on('click', '.add_new_ap', function() {
   $('#main_sp_parent').append('<div class="sub_sp_child"><div class="row" id="sub_sp_parent"><div class="col-md-3"><div class="form-group form-float"><div class="form-line"><input type="text" class="form-control" name="product_name[]" value="{{ old('product_name') }}" required><label class="form-label">Product name</label></div></div></div><div class="col-md-3"><div class="form-group form-float"><div class="form-line"><input type="text" class="form-control" name="product_quantity[]" value="{{ old('product_quantity') }}" required><label class="form-label">Product Quantity</label></div></div></div><div class="col-md-3"><div class="form-group form-float"><div class="form-line"><input type="text" class="form-control" name="product_price[]" value="{{ old('product_price') }}" required><label class="form-label">Product Price</label></div></div></div><div class="col-md-3"><div class="btn-group"><button type="button" class="btn btn-success btn-sm add_new_ap" data-toggle="tooltip" title="Add new product"><i class="fa fa-plus"></i></button><button type="button" class="btn btn-danger btn-sm remove_ap"><i class="fa fa-trash"></i></button></div></div></div></div>');
   		// return false; //prevent form submission
   });

 $('#main_sp_parent').on('click', '.remove_ap', function() {
 	$(this).parents('.sub_sp_child').slideUp(500,function(){
 		$(this).remove();
 	})
 });
</script>
@stop


