@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($region->name) }} <small>Details</small>
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
                                <td>{{ ucwords($region->name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Address : </th>
                                <td>{{ ucwords($region->address) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Remarks : </th>
                                <td>{{ $region->remarks }}</td>
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