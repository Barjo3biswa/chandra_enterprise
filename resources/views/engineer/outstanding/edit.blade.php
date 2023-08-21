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
				<h2>Edit Outstanding Report</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view all-bill-outstanding-details'))
					<li><a href="{{ route('all-bill-outstanding-details') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-bill-outstanding-details.store') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="client_id" id="client_id" required>
		                                <option value=""> Please select one client </option>
		                                @foreach($bill_clients as $bill_client)

		                                <option value="{{ $bill_client->client->id }}" data-themeid="{{ $bill_client->client->id }}" {{ old('client_id',$c_bill_follow->client_id) == $bill_client->client->id ? 'selected' : '' }}>{{ ucwords($bill_client->client->name) }}</option>

					                    @endforeach

		                            </select>
									<label class="form-label">Select Client</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="branch" id="branch" required>
		                                <option value=""> Please select one branch </option>
		                                @foreach ($bill_clients as $client)
				                        @if($client->client->branch_name==old('branch',$c_bill_follow->client->branch_name))
				                          <option value="{{ $client->client->branch_name }}" {{ (old('branch',$c_bill_follow->client->branch_name) == $client->client->branch_name) ? 'selected' : '' }}>{{ ucwords($client->client->branch_name) }}</option>
				                        @endif
				                      @endforeach
		                            </select>
									<label class="form-label">Select Branch</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="client_bill_id" id="client_bill_id" required>
		                                <option value=""> Please select bill </option>
		                               @foreach ($bill_clients as $client_billId)

				                        @if($client_billId->id==old('client_bill_id',$c_bill_follow->client_bill_id))

				                          <option value="{{ $client_billId->id }}" {{ (old('client_bill_id',$c_bill_follow->client_bill_id) == $client_billId->id) ? 'selected' : '' }}>{{ ucwords($client_billId->bill_no) }}</option>

				                        @endif

				                      @endforeach
		                            </select>
									<label class="form-label">Select Bill</label>
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
										<option value="1" {{ (old('bill_status',$c_bill_follow->bill_status) == 1) ? 'selected' : '' }}>Yet to clear payment</option>
		                            	<option value="2" {{ (old('bill_status',$c_bill_follow->bill_status) == 2) ? 'selected' : '' }}>cleared payment</option>
		                            </select>
									<label class="form-label">Select Bill Status</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="next_pay_by_date" value="{{ old('next_pay_by_date',$c_bill_follow->next_pay_by_date) }}" required>
									<label class="form-label">Next Pay By Date (if any)</label>
								</div>
							</div>
						</div>

						
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea class="form-control" name="bill_remarks" required>{{ old('bill_remarks',$c_bill_follow->bill_remarks) }}</textarea>
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

$("#client_id").change(function(){
  
  var client_id = $('option:selected', this).attr('data-themeid');

  // alert(client_id);

   
  $.ajax({
    type: "GET",
    url: "{{ route('get-branch.ajax') }}",
    data: {
      'client_id': client_id
    },

    success: function(response) {
      if(response) {

        var toAppend = '';
        var toAppendbill = '';

        toAppend +='<option value="">All Branches</option>';
        $.each(response.branchname, function(i,o){

        	console.log(o.branch_name);

          toAppend += '<option  value="'+o.branch_name+'" data-themeid="'+o.branch_name+'">'+o.branch_name+'</option>';
        });

        $('#branch').html(toAppend);


        toAppendbill +='<option value="">All Bill No</option>';
        $.each(response.client_bill_id, function(i,o){

        	console.log(o.bill_no);

          toAppendbill += '<option  value="'+o.id+'" data-themeid="'+o.id+'">'+o.bill_no+'</option>';
        });

        $('#client_bill_id').html(toAppendbill);

      }else{
        alert("No branch found");
      }
    }
  });
 });

checkNextBillDate = function(Obj){
	var $current = $(Obj);
	if ($current.val() == 1) {
		alert('value is 1');
		$("#next_pay_by_date").attr('required', '');
	}
}
</script>
@stop


