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
				<h2>Edit Amc bill details</h2>
				<ul class="header-dropdown m-r--5">
					<li><a href="{{ route('view-all-client-amc') }}" class="btn btn-success">View all</a></li>
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('raise-client-amc-update-bill',Crypt::encrypt($amc_bill->id)) }}">

					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					<input type="hidden" name="client_amc_masters_id" value="{{ $amc_bill->client_amc_masters_id }}">
					

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="bill_name" name="bill_name" placeholder="AMC bill name" value="{{ old('bill_name',$amc_bill->bill_name) }}" autocomplete="off" required>
									<label class="form-label">AMC bill name</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="bill_no" name="bill_no" placeholder="AMC bill no" value="{{ old('bill_no',$amc_bill->bill_no) }}" autocomplete="off" required>
									<label class="form-label">AMC bill no</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" id="bill_amount" name="bill_amount" placeholder="AMC bill amount" value="{{ old('bill_amount',$amc_bill->bill_amount) }}" min="0" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" maxlength="10" autocomplete="off" required>
									<label class="form-label">AMC bill amount</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" id="bill_from_date" name="bill_from_date" placeholder="AMC bill date from eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('bill_from_date',date('d-m-Y', strtotime($amc_bill->bill_from_date))) }}" autocomplete="off" required>
									<label class="form-label">AMC bill date from</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" id="bill_to_date" name="bill_to_date" placeholder="AMC bill date to eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('bill_to_date',date('d-m-Y', strtotime($amc_bill->bill_to_date))) }}" autocomplete="off" required>
									<label class="form-label">AMC bill date to</label>
								</div>
							</div>
						</div>


						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" id="bill_date" name="bill_date" placeholder="AMC bill date eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('bill_date',date('d-m-Y', strtotime($amc_bill->bill_date))) }}" autocomplete="off" required>
									<label class="form-label">AMC bill date</label>
								</div>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-12">
							<div class="form-group form-float">
                                <div class="form-line">
                                    <textarea class="form-control" id="bill_remarks" name="bill_remarks" placeholder="Amc bill remarks" required>{{ old('bill_remarks',$amc_bill->bill_remarks) }}</textarea>
                                    <label class="form-label">AMC bill Remarks</label>
                                </div>
                            </div>
						</div>
					</div>
				
					<button class="btn btn-primary waves-effect" type="submit" onclick="return confirm('Are you sure')">UPDATE</button>
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

</script>
@stop


