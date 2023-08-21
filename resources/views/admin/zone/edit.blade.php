@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update Zone</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view zone'))
					<li><a href="{{ route('view-all-zones') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-zone',Crypt::encrypt($zone->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}
					<div class="form-group form-float">
						<div class="form-line">
							<input type="text" class="form-control" name="name" value="{{ old('name',$zone->name) }}" required>
							<label class="form-label">Name</label>
						</div>
					</div>

					<div class="form-group form-float">
						<div class="form-line">
							<textarea name="remarks" cols="30" rows="5" class="form-control no-resize">{{ old('remarks',$zone->remarks) }}</textarea>
							<label class="form-label">Remarks</label>
						</div>
					</div>

					<button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

@stop



