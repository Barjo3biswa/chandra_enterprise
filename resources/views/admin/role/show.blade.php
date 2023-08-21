@extends('layouts.front')


@section('styles')
<style>
	.font_blue {
		color: #00bbd2;
	}
</style>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($users->first_name.' '.$users->middle_name.' '.$users->last_name) }} <small>Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    <table class="table table-condensed">
                        <thead>
                            <!--<tr>
                                <th></th>
                                <th></th>
                            </tr>-->
                        </thead>
                        <tbody>
							
							@foreach($user_permission_detail as $detail)
                            <tr>
                                <th scope="row" style="width: 25%;">{{ ucwords($detail->name) }} : </th>
                                <td><i class="fa fa-check fa-2x font_blue"></i></td>
                            </tr>
                           @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

@stop