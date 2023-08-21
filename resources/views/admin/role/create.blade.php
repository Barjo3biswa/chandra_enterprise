@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add permission</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view role'))
					<li><a href="{{ route('view-all-roles') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('user.permission.store') }}">
					{{ csrf_field() }}

					<div class="row">


						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<select class="form-control show-tick" name="user_id" required>
										<option value=""> Please select a user </option>
										<?php foreach ($users as $user): ?>
										<option value="{{ $user->id }}" {{ old('user_id') == "$user->id" ? 'selected' : '' }}>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }}</option>
										<?php endforeach; ?>
									</select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-8">
							<input type="checkbox" name="select_all" id="select_all" class="filled-in chk-col-cyan" />
							<label for="select_all">Select All</label>
						</div>


					</div>

					<div class="row">

						@foreach($permissions as $permission)
							@php
								$same_header = true;
								$previous_permission_header = "";
								foreach($permission->permission_roles as $new_permission){
									if($previous_permission_header != "" && ($previous_permission_header != $new_permission->role_part)){
										$same_header = false;
									}
									$previous_permission_header = $new_permission->role_part;
								}
							@endphp
							<div class="col-md-12">
							<h3 class="font-underline col-blue-grey">{{ ucwords($permission->heading) }} <span class="text-danger"><small>{{($same_header ? "(".$previous_permission_header.")" : "")}}</small></span></h3>
							</div>

							@foreach($permission->permission_roles as $pp)
								<div class="col-md-3">

									<input type="checkbox" id="permission{{$pp->id}}" name="permission[]" class="chk-col-cyan permission" value="{{$pp->id}}" />
									<label for="permission{{$pp->id}}">{{$pp->name}} <span class="text-danger"><small>{{($same_header ? " " : "(".$pp->role_part.")")}}</small></span></label>

								</div>

							@endforeach
						@endforeach


						<div class="col-md-12">
							<button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	@endsection


	@section('scripts')
	<script>
		$('#select_all').click(function() {
			var checkboxes = $('.permission');
// alert($(this).is(':checked'));
if($(this).is(':checked')) {
	checkboxes.prop("checked" , true);
} else {
	checkboxes.prop ( "checked" , false );
}
});
</script>
@stop


