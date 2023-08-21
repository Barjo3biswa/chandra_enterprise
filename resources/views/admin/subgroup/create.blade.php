@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add New Sub Group</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view sub_group'))
					<li><a href="{{ route('view-all-sub-groups') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-sub-groups.post') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="group_id" required>
		                                <option value=""> Please select one group </option>
		                                <?php foreach ($groups as $group): ?>
					                    <option value="{{ $group->id }}" {{ old('group_id') == "$group->id" ? 'selected' : '' }}>{{ ucwords($group->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
									<label class="form-label">Name</label>
								</div>
							</div>
						</div>
					</div>
				
					<div class="form-group form-float">
						<div class="form-line">
							<textarea name="remarks" cols="30" rows="5" class="form-control no-resize">{{ old('remarks') }}</textarea>
							<label class="form-label">Remarks</label>
						</div>
					</div>

					<button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

@stop


