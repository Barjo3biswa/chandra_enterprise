@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update Spare part</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view spare-parts'))
					<li><a href="{{ route('view-all-spare-parts') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-spare-parts',Crypt::encrypt($spare_part->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="name" value="{{ old('name',$spare_part->name) }}" autocomplete="off" required>
									<label class="form-label">Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="sp_code" value="{{ old('sp_code',$spare_part->sp_code) }}" >
									<label class="form-label">Spare part code</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="part_no" value="{{ old('part_no',$spare_part->part_no) }}" >
									<label class="form-label">Spare part no</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="group_id" id="group_id">
		                                <option value=""> Please select one group </option>
		                                <?php foreach ($groups as $group): ?>
					                    <option value="{{ $group->id }}" data-themeid="{{ $group->id }}" {{ old('group_id',$spare_part->group_id) == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
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
		                                 @foreach ($sgroups as $sgroup)
						                    @if($sgroup->id==old('subgroup_id',$product->subgroup_id))
						                    	<option value="{{ $sgroup->id }}" {{ (old('subgroup_id',$spare_part->subgroup_id) == $sgroup->id) ? 'selected' : '' }}>{{ ucwords($sgroup->name) }}</option>
						                    @endif
					                    @endforeach		                                
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="company_id" id="company_id">
		                                <option value=""> Please select one company </option>
		                                <?php foreach ($companies as $company): ?>
					                    <option value="{{ $company->id }}" {{ old('company_id',$spare_part->company_id) == "$company->id" ? 'selected' : '' }}>{{ ucwords($company->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>
					</div>

					<div class="row">
					
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="brand" id="brand">
		                                <option value=""> Please select one brand </option>
		                                <?php foreach ($product_brands as $product_brand): ?>
					                    <option value="{{ $product_brand->brand }}" {{ old('brand',$spare_part->brand) == "$product_brand->brand" ? 'selected' : '' }}>{{ ucwords($product_brand->brand) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>
					
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="opening_balance" value="{{ old('opening_balance',$spare_part->opening_balance) }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" maxlength="3" min="0" required>
									<label class="form-label">Opening balance</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="with_effect_from" name="with_effect_from" placeholder="With effect from eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="@if($spare_part->with_effect_from != "0000-00-00"){{ old('with_effect_from',date('d-m-Y', strtotime($spare_part->with_effect_from))) }}@endif">
									<!--<label class="form-label">DOB</label>-->
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<textarea name="tech_specification" class="form-control no-resize">{{ old('tech_specification',$spare_part->tech_specification) }}</textarea>
									<label class="form-label">Technical specification</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<textarea name="remarks" class="form-control no-resize">{{ old('remarks',$spare_part->remarks) }}</textarea>
									<label class="form-label">Remarks</label>
								</div>
							</div>
						</div>
					</div>
				
					<button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
<script>

$('#with_effect_from').Zebra_DatePicker({
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
</script>
@stop



