@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Transfer Assigned Product To Another Client</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view assign-product-to-client'))
					<li><a href="{{ route('view-all-assign-client') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">

				<form id="form_validation" method="post" action="{{ route('transfer-assign-product-to-another-client.post',Crypt::encrypt($assign_p->id)) }}">
					{{ csrf_field() }}


					<input type="hidden" name="old_product_id" value="{{ $assign_p->product_id }}">
					<input type="hidden" name="old_client_id" value="{{ $assign_p->client_id }}">

					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_name" value="{{ ucwords($assign_p->product->name) }}" disabled>
									<label class="form-label">Product name</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_code" value="{{ ucwords($assign_p->product->code) }}" disabled>
									<label class="form-label">Product code</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_serial_no" value="{{ ucwords($assign_p->product->serial_no) }}" disabled>
									<label class="form-label">Product serial no</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_group" value="{{ ucwords($assign_p->product->group->name) }}" disabled>
									<label class="form-label">Product group</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_group" value="{{ ucwords($assign_p->client->name) }}" disabled>
									<label class="form-label">Assigned client</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="product_group" value="{{ ucwords($assign_p->client->branch_name) }}" disabled>
									<label class="form-label">Assigned client branch</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="client_id" id="client_id" required>
		                                <option value=""> Please select a client to transfer</option>
		                                <?php foreach ($clients as $client): ?>
					                    <option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id') == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="branch" id="branch" required>
		                                <option value=""> Please select a branch </option>
		                               
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-primary waves-effect" id="cnt_submt" onclick="return confirm('Are you sure')" type="submit">TRANSFER</button>
						</div>
					</div>
						
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script>
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
</script>
@stop