@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add New Product</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view product'))
					<li><a href="{{ route('view-all-product') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-product.post') }}">
					{{ csrf_field() }}

					<div class="row">
						
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
									<label class="form-label">Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_code" value="{{ old('product_code') }}">
									<label class="form-label">Product code</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="serial_no" value="{{ old('serial_no') }}">
									<label class="form-label">Product serial no</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="manufacture_date" name="manufacture_date" data-zdp_readonly_element="false" placeholder="Menufacture date eg,(dd-mm-yyyy)" value="{{ old('manufacture_date') }}">
									<!--<label class="form-label">DOB</label>-->
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="date_of_purchase" name="date_of_purchase" placeholder="Date of purchase eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('date_of_purchase') }}">
									<!--<label class="form-label">DOB</label>-->
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="warranty" value="{{ old('warranty') }}" maxlength="1" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')">
									<label class="form-label">Warrenty period (in years)</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="brand" value="{{ old('brand') }}">
									<label class="form-label">Brand Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="model_no" value="{{ old('model_no') }}">
									<label class="form-label">Model no</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="equipment_no" value="{{ old('equipment_no') }}">
									<label class="form-label">Equipment no</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="group_id" id="group_id" required>
		                                <option value=""> Please select one group </option>
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
									 <select class="form-control show-tick" name="subgroup_id" id="subgroup_id" >
		                                <option value=""> Please select one sub group </option>
		                                
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="company_id" required>
		                                <option value=""> Please select one company </option>
		                                <?php foreach ($companies as $company): ?>
					                    <option value="{{ $company->id }}" {{ old('company_id') == "$company->id" ? 'selected' : '' }}>{{ ucwords($company->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
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
<script src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
<script>
$(document).ready(function() {
	$('#manufacture_date').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false,
	      
	 });

	$('#date_of_purchase').Zebra_DatePicker({
	      format: 'd-m-Y',
	      direction: false,
	     
	 });


	$("#group_id").change(function(){
  
  var group_id = $('option:selected', this).attr('data-themeid');

    // alert(group_id);

  $.ajax({
    type: "GET",
    url: "{{ route('getsubgroup.ajax.post') }}",
    data: {
      'group_id': group_id
    },

    success: function(response) {
      if(response) {

        

        var toAppend = '';

        toAppend +='<option value="">All Sub Groups</option>';
        $.each(response, function(i,o){

        	console.log(o);
          toAppend += '<option  value="'+o.id+'" {{ old('subgroup_id') == "'+o.id+'" ? 'selected' : '' }} data-themeid="'+o.id+'">'+o.name+'</option>';
        });

        $('#subgroup_id').html(toAppend);

      }else{
        alert("No sub group found");
      }
    }
  });
  });
});
</script>
@stop



