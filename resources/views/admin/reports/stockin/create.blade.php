@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
<style>
	.form-group .form-line .form-label {
		top: -10px!important;
		font-size: 12px !important;
	}
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Search stockins by</h2>
				<ul class="header-dropdown m-r--5">
					{{-- <li><a href="{{ route('view-all-groups') }}" class="btn btn-success">View all</a></li> --}}
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="get" action="{{ route('view-user-stockin-reports.store') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="user_id" id="user_id" >
										<option value=""> Please select user </option>

										@foreach ($users as $user)
						                    	<option value="{{ $user->id }}" {{ (old('user_id') == $user->id) ? 'selected' : '' }}>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name.' '.'('.$user->role.')') }}</option>
						               @endforeach
										
									</select>
								</div>
							</div>
						</div>
					</div>

					<button class="btn btn-primary waves-effect" type="submit">SEARCH</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

@stop

