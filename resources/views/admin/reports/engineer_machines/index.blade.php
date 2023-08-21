@extends('layouts.front')

@section('styles')
<style>
    .form-group .form-line .form-label {
        top: -10px !important;
        font-size: 12px !important;
    }
    .hidden{
        /* display: none; */
    }
    tr.header{
        cursor: pointer;
    }
</style>
@stop

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Engineer's Assigned Machines</h2>
                <ul class="header-dropdown m-r--5">
                    <li><a href="{{ request()->fullUrlWithQuery(['export-data' => 1]) }}" data-toggle="tooltip" data-title="Export to Excel"><button type="button" class="btn btn-sm btn-primary waves-effect"><i class="glyphicon glyphicon-export"></i> Export to Excel</button></a></li>
                </ul>
            </div>
            <div class="body">
                
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Notice !</strong> Click Engineer name or row to expand details.
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Engineer Name</th>
                            <th>Total Machines</th>
                            <th>Export</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($engineers_machine_reports as $index => $engineer)
                        <tr class="header" tabindex="-1">
                            <td>{{$index + 1}}</td>
                            <td>{{$engineer->full_name()}}</td>
                            <td class="last">{{$engineer_wise_count[$engineer->id]}}</td>
                            <td class="last"><a href="{{ request()->fullUrlWithQuery(['export-data' => 1, "engineer" => $engineer->id]) }}" data-toggle="tooltip" data-title="Export to Excel"><button type="button" class="btn btn-xs btn-primary waves-effect"><i class="glyphicon glyphicon-export"></i> Export</button></a></td>
                        </tr>
                        <tr class="hidden" tabindex="-1">
                            <td colspan="4">
                                <h4>Machines</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Product Name</th>
                                            <th>Company</th>
                                            <th>Product Code</th>
                                            <th>Serial No</th>
                                            {{-- <th>Total AMC</th>
                                            <th>AMC Done</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($engineer->assigned_engg)
                                            @foreach ($engineer->assigned_engg as $assigned)
                                                @foreach ($assigned->client->assigned_products as $index => $assigned_product)
                                                    <tr>
                                                        @if($index == 0)
                                                            <td>{{$assigned->client->name}} [{{$assigned->client->branch_name}}]</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>{{$assigned_product->product->name}}</td>
                                                        <td>{{$assigned_product->product->company->name ?? ""}}</td>
                                                        <td>{{($assigned_product->product->product_code == "NULL" ? "" : $assigned_product->product->product_code)}}</td>
                                                        <td>{{$assigned_product->product->serial_no ?? ""}}</td>
                                                        {{-- <td>{{getTotalAmc($assigned_product, $amc_counts)}}</td>
                                                        <td>{{getTotalAmcCompleted($assigned_product, $amc_completed_counts)}}</td> --}}
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-danger text-center" colspan="3">No Records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    $(document).ready(function(){
        $(document).on("click", "tr.header", function(){
            console.log("clicked");
            $(this).next("tr").toggleClass("hidden");
        })
    });
</script>
@stop