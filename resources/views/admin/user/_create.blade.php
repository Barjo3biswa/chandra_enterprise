<div class="row">

	<div class="col-md-2">
		<div class="form-group form-float">
			<div class="form-line">
				<select class="form-control show-tick" name="title" id="title" required>
					<option value="">-- Title --</option>
					<option value="Mr" {{ old('title') == "Mr" ? 'selected' : '' }}>Mr</option>
					<option value="Mrs" {{ old('title') == "Mrs" ? 'selected' : '' }}>Mrs</option>
					<option value="Miss" {{ old('title') == "Miss" ? 'selected' : '' }}>Miss</option>
					<option value="Ms" {{ old('title') == "Ms" ? 'selected' : '' }}>Ms</option>
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
				<label class="form-label">First Name</label>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}">
				<label class="form-label">Middle Name</label>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
				<label class="form-label">Last Name</label>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="emp_code" id="emp_code" value="{{ old('emp_code') }}" required>
				<label class="form-label">Emp Code</label>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control datepicker" name="dob" placeholder="DOB eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('dob') }}">
				<!--<label class="form-label">DOB</label>-->
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="pan_card_no" value="{{ old('pan_card_no') }}">
				<label class="form-label">Pan card</label>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float left50">
			<div class="form-line">
				<input type="tel" id="phone" class="form-control" name="ph_no"  maxlength="11" placeholder="Phone no" value="{{ old('ph_no') }}">
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
				<input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
				<label class="form-label">Email</label>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<select class="form-control show-tick" name="gender">
					<option value="">-- Gender --</option>
					<option value="Male" {{ old('gender') == "Male" ? 'selected' : '' }}>Male</option>
					<option value="Female" {{ old('gender') == "Female" ? 'selected' : '' }}>Female</option>
					<option value="Others" {{ old('gender') == "Others" ? 'selected' : '' }}>Others</option>
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="emp_designation" value="{{ old('emp_designation') }}">
				<label class="form-label">Designation</label>
			</div>
		</div>
	</div>

	
</div>

<div class="row">
	<div class="col-md-6">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="password" id="password" class="form-control" name="password" value="{{ old('password') }}" required>
				<label class="form-label">Password</label>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="password" class="form-control" id="password_confirmation" onblur="confirmPassword()" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="off" required>
				<label class="form-label">Confirm Password</label>
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
					<option value="admin" {{ old('role') == "admin" ? 'selected' : '' }}>Admin</option>
					<option value="manager" {{ old('role') == "manager" ? 'selected' : '' }}>Manager</option>
					<option value="engineer" {{ old('role') == "engineer" ? 'selected' : '' }}>Engineer</option>
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
					<option value="{{ $state->name }}" data-themeid="{{ $state->id }}" {{ old('state') == "$state->name" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
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
		                	<option value="{{ $dist->name }}" {{ (old('district') == $dist->name) ? 'selected' : '' }}>{{ ucwords($dist->name) }}</option>
		                @endif
		            @endforeach
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group form-float">
			<div class="form-line">
				<input type="text" class="form-control" name="pin_code" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" value="{{ old('pin_code') }}">
				<label class="form-label">Pin code</label>
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






