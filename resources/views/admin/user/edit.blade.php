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
				<h2>Update User</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view user'))
					<li><a href="{{ route('view-all-users') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-users',Crypt::encrypt($user->id)) }}">

					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

					<div class="row">

						<div class="col-md-2">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="title" id="title" required>
										<option value="">-- Title --</option>
										<option value="Mr" {{ old('title',$user->title) == "Mr" ? 'selected' : '' }}>Mr</option>
										<option value="Mrs" {{ old('title',$user->title) == "Mrs" ? 'selected' : '' }}>Mrs</option>
										<option value="Miss" {{ old('title',$user->title) == "Miss" ? 'selected' : '' }}>Miss</option>
										<option value="Ms" {{ old('title',$user->title) == "Ms" ? 'selected' : '' }}>Ms</option>
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="first_name" value="{{ old('first_name',$user->first_name) }}" required>
									<label class="form-label">First Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="middle_name" value="{{ old('middle_name',$user->middle_name) }}">
									<label class="form-label">Middle Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="last_name" value="{{ old('last_name',$user->last_name) }}">
									<label class="form-label">Last Name</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="emp_code" id="emp_code" value="{{ old('emp_code',$user->emp_code) }}" required>
									<label class="form-label">Emp Code</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="dob" placeholder="DOB eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="@if($user->dob != '0000-00-00'){{ old('dob',$user->dob) }}@endif">
									<!--<label class="form-label">DOB</label>-->
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="pan_card_no" value="{{ old('pan_card_no',$user->pan_card_no) }}">
									<label class="form-label">Pan card</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float left50">
								<div class="form-line">
									<input type="tel" id="phone" class="form-control" name="ph_no"  maxlength="11" placeholder="Phone no" value="{{ old('ph_no',$user->ph_no) }}">
									{{-- <label class="form-label">Phone no</label> --}}
								</div>
								<span id="valid-msg" class="text-green hide">✓ Valid</span>
								<span id="error-msg" class="error hide">Invalid Phone number</span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="email" class="form-control" name="email" id="email" value="{{ old('email',$user->email) }}">
									<label class="form-label">Email</label>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="gender">
										<option value="">-- Gender --</option>
										<option value="Male" {{ old('gender',$user->gender) == "Male" ? 'selected' : '' }}>Male</option>
										<option value="Female" {{ old('gender',$user->gender) == "Female" ? 'selected' : '' }}>Female</option>
										<option value="Others" {{ old('gender',$user->gender) == "Others" ? 'selected' : '' }}>Others</option>
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="emp_designation" value="{{ old('emp_designation',$user->emp_designation) }}">
									<label class="form-label">Designation</label>
								</div>
							</div>
						</div>

						
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control" name="role" required>
										<option value="">-- Please select user role --</option>
										<option value="admin" {{ old('role',$user->role) == "admin" ? 'selected' : '' }}>Admin</option>
										<option value="manager" {{ old('role',$user->role) == "manager" ? 'selected' : '' }}>Manager</option>
										<option value="engineer" {{ old('role',$user->role) == "engineer" ? 'selected' : '' }}>Engineer</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="state" id="state">
										<option value="">-- Please select state --</option>
										<?php foreach ($states as $state): ?>
										<option value="{{ $state->name }}" data-themeid="{{ $state->id }}" {{ old('state',$user->state) == "$state->name" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
										<?php endforeach; ?> 
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="district" id="district">
										<option value="">-- Please select district --</option>
										@foreach ($dists as $dist)
										@if($dist->name==old('district'))
										<option value="{{ $dist->name }}" {{ (old('district',$user->district) == $dist->name) ? 'selected' : '' }}>{{ ucwords($dist->name) }}</option>
										@endif
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="pin_code" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" value="{{ old('pin_code',$user->pin_code) }}">
									<label class="form-label">Pin code</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<textarea name="address" cols="30" rows="5" class="form-control no-resize">{{ old('address',$user->address) }}</textarea>
									<label class="form-label">Address</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<textarea name="remarks" cols="30" rows="5" class="form-control no-resize">{{ old('remarks',$user->remarks) }}</textarea>
									<label class="form-label">Remarks</label>
								</div>
							</div>
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




// function confirmEmail() {
// 	var email = document.getElementById("email").value
// 	var confemail = document.getElementById("confemail").value
// 	if(email != confemail) {
// 		alert('Email Not Matching!');
// 	}
// }

function confirmPassword() {
	var password              = document.getElementById("password").value
	var password_confirmation = document.getElementById("password_confirmation").value
	if(password != password_confirmation) {
		alert('Password Not Matching!');
		return false;
	}
	return true;
}

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
				toAppend += '<option  value="'+o.name+'" data-themeid="'+o.id+'">'+o.name+'</option>';
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





