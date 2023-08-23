@extends('layouts.front')


@section('styles')
    <link href="{!! asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') !!}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
    <style>
        .form-group .form-line .form-label {
            top: -10px !important;
        }

        .Zebra_DatePicker_Icon_Wrapper {
            width: 100% !important;
        }
    </style>
@stop

@section('content')

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Client AMC list
                    </h2>
                    <ul class="header-dropdown m-r--5">

                        <li>
                            <button type="button" data-toggle="modal" data-target="#myModal" data-backdrop="static"
                                data-keyboard="false" class="btn bg-blue waves-effect"><i class="fa fa-filter"></i>
                                Filter </button>
                        </li>

                        {{-- <li><a href="{{ route('all-client-amc-details.excel') }}" class="btn bg-brown waves-effect"> <i class="fa fa-download" aria-hidden="true"></i> Export to Excel </a></li> --}}
                        <li><button type="button" class="btn bg-brown waves-effect" onclick="ExportExcelModal(this)"> <i
                                    class="fa fa-download" aria-hidden="true"></i> Export to Excel </button></li>


                        @if (Auth::user()->can('add client amc'))
                            <li><a href="{{ route('add-new-client-amc') }}" class="btn btn-success">Add new</a></li>
                        @endif

                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Zone</th>
                                    <th>Client name</th>
                                    <th>Branch name</th>
                                    <th>AMC type</th>
                                    <th>AMC start date</th>
                                    <th>AMC end date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Zone</th>
                                    <th>Client name</th>
                                    <th>Branch name</th>
                                    <th>AMC type</th>
                                    <th>AMC start date</th>
                                    <th>AMC end date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @php
                                    // $i = 1;
                                    $colour = null;
                                @endphp
                                @foreach ($client_amcs as $key => $client_amc)
                                    @php
                                        // dd()
                                        if ($client_amc->assigned_engineers->count() > 0) {
                                            $colour = '#2B982B';
                                        }
                                        if($client_amc->amc_master_transaction->where('engineer_status',1)->count()==0){
                                            $colour = "green";
                                        }
                                        if($client_amc->amc_master_transaction->where('engineer_status',1)->count()>0 && $client_amc->amc_master_transaction->where('engineer_status',0)->count()>0){
                                            $colour = "yellow";
                                        }
                                        if($client_amc->amc_master_transaction->where('engineer_status',0)->count()==0){
                                            $colour = "yellow";
                                        }
                                    @endphp
                                    <tr style="color: {{ $colour }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ ucwords($client_amc->client->zone->name ?? 'NA') }}</td>
                                        <td>{{ ucwords($client_amc->client->name) }}</td>
                                        <td>{{ ucwords($client_amc->client->branch_name) }}</td>
                                        <td>{{ ucwords($client_amc->roster->roster_name) }}</td>
                                        <td>
                                            @if ($client_amc->amc_start_date != '0000-00-00')
                                                {{ date('d M, Y', strtotime($client_amc->amc_start_date)) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($client_amc->amc_end_date != '0000-00-00')
                                                {{ date('d M, Y', strtotime($client_amc->amc_end_date)) }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">

                                                @if (Auth::user()->can('edit client amc'))
                                                    <a href="{{ route('edit-client-amc', Crypt::encrypt($client_amc->id)) }}"
                                                        class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                        title="Edit"><i class="fa fa-edit"></i></a>
                                                @endif


                                                <a href="{{ route('show-client-amc', Crypt::encrypt($client_amc->id)) }}"
                                                    class="btn btn-sm btn-info" data-toggle="tooltip" title="Details"><i
                                                        class="fa fa-eye"></i></a>

                                                @if (Auth::user()->can('assign amc to engineer'))
                                                    <a href="{{ route('amc-assigned-to-engineers', Crypt::encrypt($client_amc->id)) }}"
                                                        class="btn btn-sm btn-success" data-toggle="tooltip"
                                                        title="Assign to engineer"><i class="fa fa-user"></i></a>
                                                @endif

                                                @if (Auth::user()->can('delete client amc'))
                                                    <a href="{{ route('destroy-client-amc', Crypt::encrypt($client_amc->id)) }}"
                                                        class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete"
                                                        onclick="return confirm('Are you sure')"><i
                                                            class="fa fa-trash"></i></a>
                                                @endif

                                                @if (Auth::user()->can('add raise-bill'))
                                                    <a href="{{ route('raise-bill-client-amc', Crypt::encrypt($client_amc->id)) }}"
                                                        class="btn btn-sm bg-teal" data-toggle="tooltip"
                                                        title="Raise bill"><i class="fa fa-rupee"></i></a>
                                                @endif


                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @php $i++ @endphp --}}
                                @endforeach
                            </tbody>
                        </table>
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
                        <h4 class="modal-title">Filter Clients AMC By</h4>
                    </div>
                    <div class="modal-body">
                        <div class="containier">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="zone_id" id="zone_id">
                                                <option value=""> Please Select Zone</option>
                                                <?php foreach ($zones as $id => $zone): ?>
                                                <option value="{{ $id }}"
                                                    {{ old('zone_id') == "$zone" ? 'selected' : '' }}>{{ ucwords($zone) }}
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label class="form-label">Please Select Zone</label>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="client_id" id="client_id">
                                                <option value=""> Please select client </option>
                                                <?php foreach ($c_group_by as $c_grp_by): ?>
                                                <option value="{{ $c_grp_by->name }}"
                                                    {{ old('client_id') == "$c_grp_by->name" ? 'selected' : '' }}>
                                                    {{ ucwords($c_grp_by->name) }}</option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label class="form-label">Select client</label>

                                        </div>
                                    </div>
                                </div>

                                {{--  <div class="col-md-4">
                 <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" list="options_branch" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" autocomplete="off">
                        <label class="form-label">Branch name</label>
                        @if (count($clients) > 0)
                        <datalist id="options_branch">
                          @foreach ($clients->unique('branch_name') as $client)
                          <option value="{{$client->branch_name}}"></option>
                          @endforeach
                        </datalist>
                        @endif
                    </div>
                </div>
            </div> --}}

                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" list="options_fin_yr" class="form-control"
                                                name="financial_year" id="financial_year"
                                                value="{{ old('financial_year') }}" autocomplete="off"
                                                placeholder="eg, yyyy-yy">
                                            <label class="form-label">Financial year</label>
                                            @if (count($client_amcs) > 0)
                                                <datalist id="options_fin_yr">
                                                    @foreach ($client_amcs->unique('financial_year') as $client_amc)
                                                        <option value="{{ $client_amc->financial_year }}"></option>
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
                                            <select class="form-control show-tick" name="roster_id" id="roster_id">
                                                <option value=""> Please select AMC type </option>
                                                <?php foreach ($rosters as $roster): ?>
                                                <option value="{{ $roster->id }}" data-themeid="{{ $roster->id }}"
                                                    {{ old('roster_id') == "$roster->id" ? 'selected' : '' }}>
                                                    {{ ucwords($roster->roster_name) }}</option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label class="form-label">Select AMC type</label>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control datepicker" id="amc_start_date"
                                                name="amc_start_date" placeholder="AMC start date eg,(dd-mm-yyyy)"
                                                data-zdp_readonly_element="false" value="{{ old('amc_start_date') }}"
                                                autocomplete="off">
                                            <label class="form-label">AMC start date</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control datepicker" id="amc_end_date"
                                                name="amc_end_date" placeholder="AMC end date eg,(dd-mm-yyyy)"
                                                data-zdp_readonly_element="false" value="{{ old('amc_end_date') }}"
                                                autocomplete="off">
                                            <label class="form-label">AMC end date</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary waves-effect" type="submit">Filter</button>
                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="exportModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Export to Excel</h4>
                </div>
                <form action="{{ route('all-client-amc-details.excel') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-line">
                                <label for="financial-years">Select Financial Year</label>
                                <select name="financial_years" id="financial-years">
                                    @foreach (getFinancialYearList() as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="{!! asset('assets/plugins/jquery-datatable/jquery.dataTables.js') !!}"></script>
    <script src="{!! asset('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') !!}"></script>


    <script src="{!! asset('assets/js/jquery-datatable.js') !!}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>
    <script>
        $('.js-basic-example').DataTable({
            pageLength: 50,
            responsive: true

        });

        $('.datepicker').Zebra_DatePicker({
            format: 'd-m-Y',
            // direction: false

        });


        //   $("#client_id").change(function(){

        // var client_id = $('option:selected', this).attr('data-themeid');


        // $.ajax({
        //   type: "GET",
        //   url: "{{ route('getclientbranch.ajax.post') }}",
        //   data: {
        //     'client_id': client_id
        //   },

        //   success: function(response) {
        //     if(response) {



        //       var toAppend = '';

        //       toAppend +='<option value="">All Branches</option>';
        //       $.each(response, function(i,o){

        //           console.log(o);
        //         toAppend += '<option  value="'+o.id+'" {{ old('branch_name') == "'+o.id+'" ? 'selected' : '' }} data-themeid="'+o.id+'">'+o.branch_name+'</option>';
        //       });

        //       $('#branch_name').html(toAppend);

        //     }else{
        //       alert("No sub group found");
        //     }
        //   }
        // });
        // });
        ExportExcelModal = function() {
            $("#exportModal").modal();
        }
    </script>
@stop
