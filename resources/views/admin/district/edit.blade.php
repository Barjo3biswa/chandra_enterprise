@extends('layouts.front')


@section('styles')
<link rel="stylesheet" type="text/css" href="{!!asset('assets/plugins/select2/select2.min.css')!!}">
<style type="text/css" media="screen">
	#phone .form-label {
		
	}
	.left50 .form-line .form-label {
		left: 50px !important;
	}

	.text-green {
		color: green;
	}
	.error {
		color: #F44336;
	}	                                                 	
</style>
@stop

@section('content')

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Update District</h2>
				<ul class="header-dropdown m-r--5">
					@if(Auth::user()->can('view disrict'))
					<li><a href="{{ route('view-all-district') }}" class="btn btn-success">View all</a></li>
					@endif
				</ul>
			</div>
			<div class="body">
				<form id="form_validation" method="POST" action="{{ route('update-district',Crypt::encrypt($districts->id)) }}">
					{{ csrf_field() }}
					{!! method_field('PATCH') !!}
		
					<div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                            <select class="form-control show-tick" name="state_id" id="state_id">
                                                    <option value="">-- Please select state --</option>
                                                    <?php foreach ($states as $state): ?>
                                                     <option value="{{ $state->id }}" data-themeid="{{ $state->id }}" {{ old('state_id',$districts->state_id) == "$state->id" ? 'selected' : '' }}>{{ ucwords($state->name) }}</option>
                                                    <?php endforeach; ?> 
                                                 </select>
                                    </div>
                                </div>
                            </div>

                        
							<div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                        <input type="text" class="form-control" name="name" value="{{$districts->name}}">
                                            <label class="form-label">District</label>
                                        </div>
                                    </div>
                                </div>
					</div>


				
					
					

					<button class="btn btn-primary waves-effect"  type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
<script type="text/javascript" src="{!!asset('assets/plugins/select2/select2.min.js')!!}"></script>
<script>

// $("#state").select2({
// 	minimumInputLength: 2
// });

// $("#district").select2();
	
	

$("#state").change(function(){
  
  var state = $('option:selected', this).attr('data-themeid');

    // alert(state);

  $.ajax({
    type: "GET",
    url: "{{ route('getdistlist.ajax.post') }}",
    data: {
      'state': state
    },

    success: function(response) {
      if(response) {

        

        var toAppend = '';

        toAppend +='<option value="">All districts</option>';
        $.each(response, function(i,o){

        	console.log(o);
          toAppend += '<option  value="'+o.name+'" {{ old('district') == "'+o.id+'" ? 'selected' : '' }} data-themeid="'+o.id+'">'+o.name+'</option>';
        });

        $('#district').html(toAppend);

      }else{
        alert("No district found");
      }
    }
  });
  });
</script>
@stop







