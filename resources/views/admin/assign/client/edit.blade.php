@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
	/*.auto_hide {
		display: none;
	}*/
	.xs_font {
		font-size: 15px!important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Edit Assign Product To Client</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view assign-product-to-client'))
					<li><a href="{{ route('view-all-assign-client') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="client_id" id="client_id" disabled="true">
		                                <option value=""> Please select a client </option>
		                                <?php foreach ($clients as $client): ?>
					                    <option value="{{ $client->name }}" data-themeid="{{ $client->name }}" {{ old('client_id',$assign_client_name->name) == "$client->name" ? 'selected' : '' }}>{{ ucwords($client->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="branch" id="branch" disabled="true">
		                                <option value=""> Please select a branch </option>
		                               @foreach ($clients as $client)
				                        @if($client->branch_name==old('branch',$assign_client_name->branch_name))
				                          <option value="{{ $client->branch_name }}" {{ (old('branch',$assign_client_name->branch_name) == $client->branch_name) ? 'selected' : '' }}>{{ ucwords($client->branch_name) }}</option>
				                        @endif
				                      @endforeach
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>
					</div>
		
					<div class="row">
						<div class="col-md-12">
							
						
					
						<div class="body table-responsive no_data">

                            <table class="table" id="p_details">
                            	
                                <thead>
                                    <tr>
                                    	<th>#</th>
                                    	<th>Product name</th>
                                    	<th>Serial code</th>
                                    	<th>Company</th>
                                    	<th>Group</th>
                                    	<th>Date of install</th>
                                    	<th>Edit</th>
                                    	<th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @php $i=1 @endphp
                                  @foreach($assign_product as $assgn_p) 
                                   <tr>
                                   	<td>{{ $i }}</td>
                                   	<td>{{ ucwords($assgn_p->product->name) }}</td>
                                   	<td>{{ $assgn_p->product->serial_no }}</td>
                                   	<td>{{ ucwords($assgn_p->product->company->name) }}</td>
                                   	<td>{{ ucwords($assgn_p->product->group->name) }}</td>
                                   	<td>
                                   		@if($assgn_p->date_of_install != null)
		                                	{{ date('d M, Y', strtotime($assgn_p->date_of_install)) }}
		                                @endif
                                   	</td>
                                   	<td>
                                   		@if(Auth::user()->can('edit product assign-product-to-client'))
                               			<form method="get" action="{{ route('product-edit-assign-to-client',Crypt::encrypt($assgn_p->product_id)) }}">
										{{ csrf_field() }}

										<input type="hidden" name="assgn_client_id1" value="{{ $assign_client_name->id }}">

                               			<button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil xs_font"></i></button>

                               			</form>
 										@endif
                                   	</td>
                                   	<td>
                                   		@if(Auth::user()->can('delete product assign-product-to-client'))
                                   		<form method="get" action="{{ route('delete-product-assign-to-client',Crypt::encrypt($assgn_p->product_id)) }}">
												{{ csrf_field() }}

											<input type="hidden" name="assgn_client_id" value="{{ $assign_client_name->id }}">
												
                                   			<button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure')"><i class="fa fa-trash xs_font"></i></button>
                                   		</form>
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

					{{-- <button class="btn btn-primary waves-effect" type="submit">UPDATE</button> --}}
				
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

 </script>
@stop

