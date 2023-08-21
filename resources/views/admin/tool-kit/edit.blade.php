@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update Tool-kit</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view tool-kit'))
					<li><a href="{{ route('view-all-tool-kit') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-tool-kit',Crypt::encrypt($tool_kit->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="name" value="{{ old('name',$tool_kit->name) }}" required>
									<label class="form-label">Name</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="quantity_to_be_issued" value="{{ old('quantity_to_be_issued',$tool_kit->quantity_to_be_issued) }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" maxlength="2" required>
									<label class="form-label">Quantity to be issued</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group form-float">
								<div class="form-line">
									<input type="text" class="form-control" name="remarks" value="{{ old('remarks',$tool_kit->remarks) }}">
									<label class="form-label">Remarks</label>
								</div>
							</div>
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



