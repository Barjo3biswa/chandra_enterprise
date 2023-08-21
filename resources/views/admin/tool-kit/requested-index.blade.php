@extends('layouts.front')


@section('styles')
<link href="{!!asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')!!}"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/default/zebra_datepicker.min.css">
<style>
    .Zebra_DatePicker_Icon_Wrapper {
        width: 100% !important;
    }
    .table {
        font-size: 12px;
    }
    .created{
        background: #CCCCCC7A
    }
    .issued{
        background: #2592109E;
    }
</style>
@stop

@section('content')
<div class="row clearfix dont-print">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2><i class="fa fa-filter"></i> Filter</h2>
            </div>
            <div class="body">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="date_from">Requested Date from</label>
                                    <input type="text" class="form-control datepicker1" placeholder="DD-MM-YYYY" id="date_from" name="date_from" value="{{request()->get("date_from")}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="date_to">Requested Date to</label>
                                    <input type="text" class="form-control datepicker2" placeholder="DD-MM-YYYY" id="date_to" name="date_to"  value="{{request()->get("date_to")}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <label for="date_to">Select Engineer</label>
                                    @php 
                                    $unique_engineers = $requested_toolkits->map(function($item){
                                        return [
                                            "id"  => $item->requested_by->id,
                                            "name" => $item->requested_by->full_name()
                                        ];
                                    })->unique();
                                    @endphp
                                    <select name="engineer_id" id="engineer_id">
                                        <option value="">--SELECT--</option>
                                        @if($requested_toolkits->count())
                                            @foreach ( $unique_engineers as $engineer)
                                                <option value="{{$engineer["id"]}}" {{$engineer["id"] == request()->get("engineer_id") ? "selected" : ""}}>{{$engineer["name"]}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group" style="padding-top:25px;">
                                <button class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> Search</button>           
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                   Requested items from engineer
                </h2>
                <ul class="header-dropdown m-r--5">
                        <a href="{{request()->fullUrlWithQuery(['export-data' => 1])}}" class="btn btn-success btn-sm"><i class="fa fa-2x fa-file-excel-o"></i> Export to Excel</a>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item name</th>
                                <th>Requested By</th>
                                <th>Request for</th>
                                <th>Requested at</th>
                                <th>Remarks</th>
                                <th>Issued At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Item name</th>
                                <th>Requested By</th>
                                <th>Request for</th>
                                <th>Requested at</th>
                                <th>Remarks</th>
                                <th>Issued at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @php $i=1 @endphp
                            @foreach($requested_toolkits as $requested_kit)
                            <tr class="{{$requested_kit->status}}">
                                <td>{{ $i }}</td>
                                <td>{{ ucwords($requested_kit->item->name ?? $requested_kit->request ?? "NA") }}</td>
                                <td>{{ $requested_kit->requested_by->full_name() }}</td>
                                <td>{{ ucwords(str_replace("_", " ", $requested_kit->request_for)) }}</td>
                                <td>{{ date("d-m-Y h:i a", strtotime($requested_kit->created_at)) }}</td>
                                <td>{{ $requested_kit->remarks ?? "NA" }}</td>
                                <td>{{ ($requested_kit->issued_at ? date("d-m-Y h:i a") :  "---") }}</td>
                                <td>{{ $requested_kit->status ?? "NA" }}</td>
                                <td>
                                    @if($requested_kit->status == "created")
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Update Status" data-action="{{ route('toolkit-requested-update',Crypt::encrypt($requested_kit->id)) }}" onclick="updateRequest(this)"><i
                                                        class="fa fa-exchange"></i></button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @php $i++ @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateRequest">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Request Status</h4>
            </div>
            <form action="#" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="status">Select Status</label>
                            <select name="status" id="status" required class="form-control">
                                <option value="">--SELECT--</option>
                                <option value="issued">Issued</option>
                                <option value="deleted">Deleted</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            <label for="date-picker">issued Date</label>
                            <input type="text" class="form-control datepicker" required placeholder="date" id="date-picker" name="issued_at">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            <label for="remark">Remarks</label>
                            <textarea name="remark" id="remark" cols="30" rows="4"  class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
    $(document).ready(function(){
        $('.datepicker').Zebra_DatePicker({
            format: 'd-m-Y H:i',
            direction: false,
        });
        $('.datepicker1').Zebra_DatePicker({
            format: 'd-m-Y',
            direction: false,
            pair: $('.datepicker2')
        });
        $('.datepicker2').Zebra_DatePicker({
            format: 'd-m-Y',
            direction: 1
        });
    });
    var table = $('.js-basic-example').DataTable({
        pageLength: 50,
        responsive: true,
        orderCellsTop: true,
        fixedHeader: true
    });
    updateRequest = function(Obj){
        var $obj = $(Obj);
        console.log($obj.data("action"));
        $modal_form = $("#updateRequest").find("form");
        $modal_form.prop("action", $obj.data("action"));
        $modal_form.find('input, select, textarea').not($modal_form.find("input[type='hidden']")).val("");
        $("#updateRequest").modal();
    }
</script>
@stop