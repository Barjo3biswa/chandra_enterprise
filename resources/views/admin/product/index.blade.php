@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
<style>
.Zebra_DatePicker_Icon_Wrapper {
    width: 100%!important;
}
.js-basic-example{
    font-size: 13px;
}
</style>
@stop

@section('content')

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Product <small>{!!request()->get('deactivated_product') == 1 ? "Deactivated" : ""!!}</small>
                </h2>
                <ul class="header-dropdown m-r--5">

                    <li>
                        <button type="button"  data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                        Filter </button>
                    </li>
                    @if(!request()->get('deactivated_product'))
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(["deactivated_product" => 1]) }}" class="btn btn-danger waves-effect"> <i class="fa fa-list-alt" aria-hidden="true"></i> Deactivated Products </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ request()->url() }}" class="btn btn-success waves-effect"> <i class="fa fa-list-alt" aria-hidden="true"></i> All Products </a>
                        </li>

                    @endif
                    <li><a href="{{ route('product-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li>

                    @if(Auth::user()->can('add product'))
                    <li><a href="{{ route('add-new-product') }}" class="btn btn-success">Add new</a></li>
                    @endif

                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Serial no</th>
                                <th>Brand</th>
                                <th>Model no</th>
                                <th>Equipment no</th>
                                <th>Assigned Branch</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Serial no</th>
                                <th>Brand</th>
                                <th>Model no</th>
                                <th>Equipment no</th>
                                <th>Assigned Branch</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($products as $k => $product)
                            <tr>
                                <td>{{ (($products->currentPage() - 1 ) * $products->perPage() ) + ($k +1) }}</td>
                                <td>{{ ucwords($product->name) }}</td>
                                <td>
                                    {{ $product->product_code }}
                                </td>
                                <td>{{ $product->serial_no }}</td>
                                <td>{{ $product->brand }}</td>
                                <td>{{ $product->model_no }}</td>
                                <td>{{ $product->equipment_no }}</td>
                                @php
                                    $branches = $product->assigned_branch->map(function($item){
                                        return $item->name."-".$item->branch_name;
                                    })->toArray();
                                @endphp
                                <td>{{ ($branches ? implode(",", $branches) : "Not Assigned") }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if(Auth::user()->can('edit product'))
                                        <a href="{{ route('edit-product',Crypt::encrypt($product->id)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-container="body" title="Edit"><i class="fa fa-edit"></i></a>
                                        @endif

                                        <a href="{{ route('show-product',Crypt::encrypt($product->id)) }}" class="btn btn-sm btn-info" data-toggle="tooltip" data-container="body" title="Details"><i class="fa fa-eye"></i></a>

                                        {{-- @if(Auth::user()->can('delete product'))
                                        <a href="{{ route('destroy-product',Crypt::encrypt($product->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-container="body" title="Delete" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
                                        @endif --}}
                                        @if($product->status == 2)
                                            @if(Auth::user()->can('delete product'))
                                            <a href="{{ route('activate-product', Crypt::encrypt($product->id)) }}" class="btn btn-sm btn-success" data-toggle="tooltip"   data-container="body" title="Activate product" onclick="return confirm('Are you sure')"><i class="fa fa-check"></i></a>
                                            @endif
                                            @if(Auth::user()->can('delete product'))
                                            <a href="{{ route('destroy-product',Crypt::encrypt($product->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip"  data-container="body" title="Delete product" onclick="return confirm('Are you sure')"><i class="fa fa-trash-o"></i></a>
                                            @endif
                                        @elseif($product->status == 1)                                       
                                            @if(Auth::user()->can('delete product'))
                                            <a href="{{ route('deactivate-product',Crypt::encrypt($product->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip"  data-container="body" title="Deactivate product" onclick="return confirm('Are you sure')"><i class="fa fa-times"></i></a>
                                            @endif
                                        @endif
                                        @if($product->assigned_product_to_client)
                                    <a href="{{route('transfer-assign-product-to-another-client', Crypt::encrypt($product->assigned_product_to_client->id))}}" title="Transfer product" class="btn bg-light-green waves-effect" data-toggle="tooltip" data-container="body"><i class="fa fa-exchange"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @php $i++ @endphp
                            @endforeach 
                        </tbody>
                    </table>
                    {!!$products->appends(request()->all())->links()!!}
                </div>
            </div>
        </div>
    </div>
</div>




<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="" method="get">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Filter Products By</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" list="options" class="form-control" name="product_id" id="product_id" value="{{ old('product_id') }}" autocomplete="off">
                                    <label class="form-label">Product name</label>
                                    @if(count($products) > 0)
                                    <datalist id="options">
                                        @foreach($products->unique("name") as $product)
                                        <option value="{{$product->name}}"></option>
                                        @endforeach
                                    </datalist>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" list="options_model_no" class="form-control" name="model_no" id="model_no" value="{{ old('model_no') }}" autocomplete="off">
                                    <label class="form-label">Model no</label>
                                    @if(count($p_model_no) > 0)
                                    <datalist id="options_model_no">
                                        @foreach($p_model_no->unique("model_no") as $p_modelno)
                                        <option value="{{$p_modelno->model_no}}"></option>
                                        @endforeach
                                    </datalist>
                                    @endif
                                </div>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" list="options_serial_no" class="form-control" name="serial_no" id="serial_no" value="{{ old('serial_no') }}" autocomplete="off">
                                    <label class="form-label">Serial no</label>
                                    @if(count($p_serial_no) > 0)
                                    <datalist id="options_serial_no">
                                        @foreach($p_serial_no->unique("serial_no") as $p_serial)
                                        <option value="{{$p_serial->serial_no}}"></option>
                                        @endforeach
                                    </datalist>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="product_code" value="{{ old('product_code') }}">
                                    <label class="form-label">Product code</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" list="options_group_id" class="form-control" name="group_id" id="group_id" value="{{ old('group_id') }}" autocomplete="off">
                                    <label class="form-label">Group</label>
                                    @if(count($p_group) > 0)
                                    <datalist id="options_group_id">
                                        @foreach($p_group->unique("group_id") as $p_grp)
                                        <option value="{{$p_grp->group->name}}"></option>
                                        @endforeach
                                    </datalist>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" list="options_company_id" class="form-control" name="company_id" id="company_id" value="{{ old('company_id') }}" autocomplete="off">
                                    <label class="form-label">Company</label>
                                    @if(count($p_company) > 0)
                                    <datalist id="options_company_id">
                                        @foreach($p_company->unique("company_id") as $p_compny)
                                        <option value="{{$p_compny->company->name}}"></option>
                                        @endforeach
                                    </datalist>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" list="options_brand" class="form-control" name="brand" id="brand" value="{{ old('brand') }}" autocomplete="off">
                                    <label class="form-label">Brand</label>
                                    @if(count($p_brands) > 0)
                                    <datalist id="options_brand">
                                        @foreach($p_brands->unique("brand") as $p_brand)
                                        <option value="{{$p_brand->brand}}"></option>
                                        @endforeach
                                    </datalist>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="date_of_purchase" name="date_of_purchase" placeholder="Date of purchase eg,(dd-mm-yyyy)" data-zdp_readonly_element="false" value="{{ old('date_of_purchase') }}">
                                    <!--<label class="form-label">DOB</label>-->
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="manufacture_date" name="manufacture_date" data-zdp_readonly_element="false" placeholder="Menufacture date eg,(dd-mm-yyyy)" value="{{ old('manufacture_date') }}">
                                    <!--<label class="form-label">DOB</label>-->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary waves-effect"  type="submit">Filter</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                </div>
            </form>
        </div>

    </div>
</div>

@endsection


@section('scripts')
<script src="{!!asset('assets/plugins/jquery-datatable/jquery.dataTables.js')!!}"></script>
<script src="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')!!}"></script>
<script src="{!!asset('assets/js/jquery-datatable.js')!!}"></script>
<script src="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/zebra_datepicker.min.js"></script>
<script>
    $('.js-basic-example').DataTable({
        "paging": false,
        responsive: true

    });
    // var $data_table = $('.js-basic-example');
    // $data_table.DataTable ({
    //     "data" : table_data,
    //     "columns" : [
    //         { "data" : "sl" },
    //         { "data" : "name" },
    //         { "data" : "product_code" },
    //         { "data" : "serial_no" },
    //         { "data" : "brand" },
    //         { "data" : "model_no" },
    //         { "data" : "equipment_no" },
    //         { "data" : "action" }
    //     ]
    // });

    $('#manufacture_date').Zebra_DatePicker({
        format: 'd-m-Y',
        direction: false,

    });

    $('#date_of_purchase').Zebra_DatePicker({
        format: 'd-m-Y',
        direction: false,

    });

</script>
@stop
