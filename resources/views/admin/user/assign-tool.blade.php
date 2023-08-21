@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-cyan">
				<h2>
					{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }} <small>Assign tool-kits</small>
				</h2>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('user-assign-tools.post', Crypt::encrypt($user->id)) }}">
					{{ csrf_field() }}
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-condensed">
								<thead>
								</thead>
								<tbody>
									<tr>
										<th scope="row" style="width: 10%;">Name : </th>
										<td>{{ ucwords($user->first_name.' '.$user->middle_name.' '.$user->last_name) }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-12">
						<input type="checkbox" name="select_all" id="select_all" class="filled-in chk-col-cyan" />
						<label for="select_all">Select All</label>
					</div>
				</div>

				<div class="row">
					@foreach($tools as $key => $pp)
					<div class="col-md-4">

						<input type="checkbox" id="tool_kit{{$pp->id}}" name="tool_kit[{{$key}}]" class="chk-col-cyan tool_kit" value="{{ old('tool_kit[$key]',$pp->id) }}" />
						<label for="tool_kit{{$pp->id}}">{{$pp->name}}</label>

					</div>

					<div class="col-md-2">
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" id="quantity_to_be_issued{{$pp->id}}" name="quantity_to_be_issued[{{$key}}]" value="{{ old('quantity_to_be_issued[$key]',$pp->quantity_to_be_issued) }}" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" maxlength="2">
								<label class="form-label">Quantity</label>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group form-float">
							<div class="form-line">
								<input type="text" class="form-control" id="remarks{{$pp->id}}" name="remarks[{{$key}}]" value="{{ old('remarks[$key]',$pp->remarks) }}">
								<label class="form-label">Remarks</label>
							</div>
						</div>
					</div>

					@endforeach

					<div class="col-md-12">
						<button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
					</div>
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
		var checkboxes = $('.tool_kit');
	// alert($(this).is(':checked'));
	if($(this).is(':checked')) {
		checkboxes.prop("checked" , true);
	} else {
		checkboxes.prop ( "checked" , false );
	}
	});
</script>
@stop