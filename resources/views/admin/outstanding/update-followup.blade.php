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
				<h2>Update Outstanding followup Report</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view client outstanding bill'))
					<li><a href="{{ route('view-all-client-outstanding-bill') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-followup-client-outstanding-bill',Crypt::encrypt($c_bill_follow->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					<input type="hidden" name="client_id" value="{{ $c_bill_follow->client_id }}">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" id="client_id" disabled>
		                                <option value=""> Please select one client </option>
		                                @foreach($clients as $client)

		                                	<option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id',$c_bill_follow->client->name) == $client->name ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>

					                    @endforeach

		                            </select>
									<label class="form-label">Select Client</label>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" id="branch" disabled>
		                                <option value=""> Please select one branch </option>
		                                @foreach ($clients as $clnt)
				                        @if($clnt->branch_name==old('branch',$c_bill_follow->client->branch_name))
				                          <option value="{{ $clnt->branch_name }}" {{ (old('branch',$c_bill_follow->client->branch_name) == $clnt->branch_name) ? 'selected' : '' }}>{{ ucwords($clnt->branch_name) }}</option>
				                        @endif
				                      @endforeach
		                            </select>
									<label class="form-label">Select Branch</label>
								</div>
							</div>
						</div>
	
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="bill_no" value="{{ old('bill_no',$c_bill_follow->bill_no) }}" disabled>
									<label class="form-label">Bill No</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="bill_amount" value="{{ old('bill_amount',$c_bill_follow->bill_amount) }}" disabled>
									<label class="form-label">Bill Amount</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="bill_date" value="{{ old('bill_date',date('d-m-Y', strtotime($c_bill_follow->bill_date))) }}" disabled>
									<label class="form-label">Bill Pay By Date</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="bill_status" id="bill_status" onchange="checkNextBillDate(this)">
		                                <option value=""> Please select bill status </option>
										<option value="1" {{ (old('bill_status') == 1) ? 'selected' : '' }}>Yet to clear payment</option>
		                            	<option value="2" {{ (old('bill_status') == 2) ? 'selected' : '' }}>cleared payment</option>
		                            </select>
									<label class="form-label">Select Bill Status</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="next_pay_by_date" value="{{ old('next_pay_by_date') }}" required>
									<label class="form-label">Next Pay By Date (if any)</label>
								</div>
							</div>
						</div>

						
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea class="form-control" name="bill_remarks" required>{{ old('bill_remarks') }}</textarea>
                                        <label class="form-label">Bill Remarks</label>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
<script>

$('.datepicker').Zebra_DatePicker({
  format: 'd-m-Y',
  // direction: false,
 
});

checkNextBillDate = function(Obj){
	var $current = $(Obj);
	if ($current.val() == 1) {
		// alert('value is 1');
		$("#next_pay_by_date").attr('required', '');
	}
}
</script>
@stop


