@extends('layouts.front')


@section('styles')
<link rel="stylesheet" type="text/css" href="{!!asset('assets/plugins/select2/select2.min.css')!!}">
<style type="text/css" media="screen">
	
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
				<h2>Add New Client</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view client'))
					<li><a href="{{ route('view-all-client') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-client.post') }}">
					{{ csrf_field() }}

					<div class="row">
					
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" list="options" class="form-control" name="name" id="client_name" value="{{ old('name') }}" autocomplete="off" required>
									<label class="form-label">Full name</label>
									@if(count($clients) > 0)
									<datalist id="options">
					                  @foreach($clients->unique("name") as $client)
					                  <option value="{{$client->name}}"></option>
					                  @endforeach
					                </datalist>
					                @endif
								</div>
							</div>
						</div>

						<div class="col-md-6">
							  <div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="branch_name" value="{{ old('branch_name') }}" autocomplete="off" required>
									<label class="form-label">Branch name</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="zone_id" id="zone_id" required="required">
		                               <option value="">-- Please select zone --</option>
		                               <?php foreach ($zones as $zone): ?>
					                    <option value="{{ $zone->id }}" data-themeid="{{ $zone->id }}" {{ old('zone_id') == "$zone->id" ? 'selected' : '' }}>{{ ucwords($zone->name) }}</option>
					                   <?php endforeach; ?> 
		                            </select>
	                            </div>
	                        </div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="region_id" id="region_id">
		                               <option value="">-- Please select region --</option>
		                               <?php foreach ($regions as $region): ?>
					                    <option value="{{ $region->id }}" data-themeid="{{ $region->id }}" {{ old('region_id') == "$region->id" ? 'selected' : '' }}>{{ ucwords($region->name) }}</option>
					                   <?php endforeach; ?> 
		                            </select>
	                            </div>
	                        </div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
									<label class="form-label">Email</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float left50">
								<div class="form-line">
									<input type="tel" id="phone" class="form-control " name="ph_no"  maxlength="11" placeholder="Client phone no" value="{{ old('ph_no') }}">
									{{-- <label class="form-label">Phone no</label> --}}
								</div>
								<span id="valid-msg" class="text-green hide">✓ Valid</span>
    							<span id="error-msg" class="error hide">Invalid Phone number</span>
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
					                    <option value="{{ $state->name }}" data-themeid="{{ $state->id }}" {{ old('state') == "$state->name" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
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
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="pin_code" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" value="{{ old('pin_code') }}">
									<label class="form-label">Pin code</label>
								</div>
							</div>
						</div>
					</div>

						
					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="contact_person_1_name" value="{{ old('contact_person_1_name') }}">
									<label class="form-label">Contact person 1 name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="email" class="form-control" name="contact_person_1_email" value="{{ old('contact_person_1_email') }}">
									<label class="form-label">Contact person 1 email</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="tel" class="form-control" maxlength="10" name="contact_person_1_ph_no" id="contact_person_1_ph_no" value="{{ old('contact_person_1_ph_no') }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')">
									<label class="form-label">Contact person 1 phone no</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="contact_person_2_name" value="{{ old('contact_person_2_name') }}">
									<label class="form-label">Contact person 2 name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="email" class="form-control" name="contact_person_2_email" value="{{ old('contact_person_2_email') }}">
									<label class="form-label">Contact person 2 email</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="tel" class="form-control" maxlength="10" name="contact_person_2_ph_no" id="contact_person_2_ph_no" value="{{ old('contact_person_2_ph_no') }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')">
									<label class="form-label">Contact person 2 phone no</label>
								</div>
							</div>
						</div>
					</div>

						
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<textarea name="address" cols="30" rows="5" class="form-control no-resize">{{ old('address') }}</textarea>
									<label class="form-label">Address</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<textarea name="remarks" cols="30" rows="5" class="form-control no-resize">{{ old('remarks') }}</textarea>
									<label class="form-label">Remarks</label>
								</div>
							</div>
						</div>
				
					</div>

			
					<button class="btn btn-primary waves-effect"  type="submit">SUBMIT</button>
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









