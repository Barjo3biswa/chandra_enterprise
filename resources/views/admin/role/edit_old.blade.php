@extends('layouts.front')


@section('styles')
<style>

</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update permission for {{ ucwords($users->title) }} {{ ucwords($users->first_name.' '.$users->middle_name.' '.$users->last_name) }}</h2>
				<ul class="header-dropdown m-r--5">
					<li><a href="{{ route('view-all-roles') }}" class="btn btn-success">View all</a></li>
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-role', Crypt::encrypt($users->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}

				
					<div class="row">
						<div class="col-md-12">
							<h5>All permissions given to {{ ucwords($users->title) }} {{ ucwords($users->first_name.' '.$users->middle_name.' '.$users->last_name) }}</h5>
						</div>
						
						@foreach($user_permission_detail as $prmssn)

							<div class="col-md-3">

								<i class="fa fa-check fa-2x col-cyan"></i> {{$prmssn->name}}

								{{-- <input type="checkbox" id="prmssn{{$prmssn->id}}" name="prmssn[]" class="chk-col-cyan" value="{{$prmssn->name}}" {{ old('prmssn[]',$prmssn->name) == $prmssn->name ? 'checked' : '' }} readonly />

                                <label for="prmssn{{$prmssn->id}}">{{$prmssn->name}}</label> --}}

							
							</div>
				   		@endforeach
					</div>

				<hr>	

				<div class="row">
					<div class="col-md-6">
						<h5>Add new permissions if needed</h5>
					</div>
					<div class="col-md-6">
						<input type="checkbox" name="select_all" id="select_all" class="filled-in chk-col-cyan" />
                        <label for="select_all">Select All</label>
					</div>
				</div>
				
				
				<div class="row">
				   @foreach($permissions as $permission)

							<div class="col-md-3">

								<input type="checkbox" id="permission{{$permission->id}}" name="permission[]" class="chk-col-cyan permission" value="{{$permission->name}}" />

                                <label for="permission{{$permission->id}}">{{$permission->name}}</label>
			
						   </div>
				   @endforeach
				</div>

						
				
					<button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
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


