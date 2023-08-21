@extends('layouts.front')


@section('styles')

@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($sgrp->name) }} <small>Details</small>
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
                                <th scope="row" style="width: 18%;">Group Name : </th>
                                <td>{{ ucwords($sgrp->group->name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Name : </th>
                                <td>{{ ucwords($sgrp->name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="width: 18%;">Remarks : </th>
                                <td>{{ $sgrp->remarks }}</td>
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