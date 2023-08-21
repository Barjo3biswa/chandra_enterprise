@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Add New Sub Menu</h2>
				<ul class="header-dropdown m-r--5">
					<li><a href="{{ route('view-all-sub-menues') }}" class="btn btn-success">View all</a></li>
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('add-new-sub-menues.post') }}">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									 <select class="form-control show-tick" name="menu_id" required>
		                                <option value=""> Please select one menu </option>
		                                <?php foreach ($menus as $menu): ?>
					                    <option value="{{ $menu->id }}" {{ old('menu_id') == "$menu->id" ? 'selected' : '' }}>{{ ucwords($menu->name) }}</option>
					                    <?php endforeach; ?>
		                            </select>
									{{-- <label class="form-label">Select Group</label> --}}
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
									<label class="form-label">Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="route" value="{{ old('route') }}" required>
									<label class="form-label">URL</label>
								</div>
							</div>
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


