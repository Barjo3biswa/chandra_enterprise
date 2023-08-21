@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
	.form-group .form-line .form-label {
		top: -10px!important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>User Location track Details</h2>
			</div>
			<div class="body">
				<form id="form_validation" method="get" action="{{ route('details-engineer-track-location') }}">
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="user_id" required>
		                                <option value=""> Please select a user </option>
		                                <?php foreach ($users as $user): ?>
					                    <option value="{{ Crypt::encrypt($user->id) }}" {{ old('user_id') == "$user->id" ? 'selected' : '' }}>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name.' '.'('.$user->role.')') }}</option>
					                    <?php endforeach; ?>
		                            </select>
									<label class="form-label">Select User</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control datepicker" name="track_date" value="{{ date('d-m-Y') }}" required>
									<label class="form-label">Track Date From</label>
								</div>
							</div>
						</div>
					</div>
				
					<button class="btn btn-primary waves-effect" type="submit">VIEW</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
<script type="text/javascript">
	$('.datepicker').Zebra_DatePicker({
		// direction: 1,
		format: 'd-m-Y',
		direction: false
	});
</script>
@stop


