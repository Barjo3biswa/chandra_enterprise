@extends('layouts.front')


@section('styles')
<link rel="stylesheet" type="text/css" href="{!!asset('assets/plugins/select2/select2.min.css')!!}">
<style type="text/css" media="screen">
	#phone .form-label {
		
	}
	.left50 .form-line .form-label {
		left: 50px !important;
	}

	.text-green {
		color: green;
	}
	.error {
		color: #F44336;
	}	                                                 	
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update Company</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view company'))
					<li><a href="{{ route('view-all-users') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-company',Crypt::encrypt($company->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="name" value="{{ old('name',$company->name) }}" required>
									<label class="form-label">Full Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float left50">
								<div class="form-line">
									<input type="tel" id="phone" class="form-control" name="ph_no"  maxlength="11" placeholder="Phone no" value="{{ old('ph_no',$company->ph_no) }}">
									{{-- <label class="form-label">Phone no</label> --}}
								</div>
								<span id="valid-msg" class="text-green hide">✓ Valid</span>
    							<span id="error-msg" class="error hide">Invalid Phone number</span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="email" class="form-control" name="email" id="email" value="{{ old('email',$company->email) }}">
									<label class="form-label">Email</label>
								</div>
							</div>
						</div>
					</div>

						
					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="state" id="state">
		                               <option value="">-- Please select state --</option>
		                               <?php foreach ($states as $state): ?>
					                    <option value="{{ $state->name }}" data-themeid="{{ $state->id }}" {{ old('state',$company->state) == "$state->name" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
					                   <?php endforeach; ?> 
		                            </select>
	                            </div>
	                        </div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control" name="district" id="district">
										<option value="">-- Please select district --</option>
										@foreach ($dists as $dist)
						                    @if($dist->name==old('district',$company->district))
						                    	<option value="{{ $dist->name }}" {{ (old('district',$company->district) == $dist->name) ? 'selected' : '' }}>{{ ucwords($dist->name) }}</option>
						                    @endif
					                    @endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="pin_code" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" value="{{ old('pin_code',$company->pin_code) }}">
									<label class="form-label">Pin code</label>
								</div>
							</div>
						</div>
					</div>

						
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="gst_no" value="{{ old('gst_no',$company->gst_no) }}">
									<label class="form-label">GST no</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="pan_card_no" value="{{ old('pan_card_no',$company->pan_card_no) }}">
									<label class="form-label">Pan card no</label>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group form-float">
						<div class="form-line">
							<textarea name="address" cols="30" rows="5" class="form-control no-resize">{{ old('address',$company->address) }}</textarea>
							<label class="form-label">Address</label>
						</div>
					</div>

					<div class="form-group form-float">
						<div class="form-line">
							<textarea name="remarks" cols="30" rows="5" class="form-control no-resize">{{ old('remarks',$company->remarks) }}</textarea>
							<label class="form-label">Remarks</label>
						</div>
					</div>

					

					<button class="btn btn-primary waves-effect"  type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script type="text/javascript" src="{!!asset('assets/plugins/select2/select2.min.js')!!}"></script>
<script>

// $("#state").select2({
// 	minimumInputLength: 2
// });

// $("#district").select2();
	
	

$("#state").change(function(){
  
  var state = $('option:selected', this).attr('data-themeid');

    // alert(state);

  $.ajax({
    type: "GET",
    url: "{{ route('getdistlist.ajax.post') }}",
    data: {
      'state': state
    },

    success: function(response) {
      if(response) {

        

        var toAppend = '';

        toAppend +='<option value="">All districts</option>';
        $.each(response, function(i,o){

        	console.log(o);
          toAppend += '<option  value="'+o.name+'" {{ old('district') == "'+o.id+'" ? 'selected' : '' }} data-themeid="'+o.id+'">'+o.name+'</option>';
        });

        $('#district').html(toAppend);

      }else{
        alert("No district found");
      }
    }
  });
  });
</script>
@stop







