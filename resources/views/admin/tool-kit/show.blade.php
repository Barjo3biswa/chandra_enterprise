@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($tool_kit->name) }} <small>Details</small>
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
                            <tr>
                                <th scope="row" style="width: 18%;">Name : </th>
                                <td>{{ ucwords($tool_kit->name) }}</td>

                                <th scope="row" style="width: 18%;">Tool kit code : </th>
                                <td>{{ $tool_kit->tool_kit_code }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Quantity to be issued : </th>
                                <td>{{ $tool_kit->quantity_to_be_issued }}</td>
                            </tr>
                           
                            <tr>
                                <th scope="row" style="width: 18%;">Remarks : </th>
                                <td>{{ ucwords($tool_kit->remarks) }}</td>
                            </tr>
                           
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