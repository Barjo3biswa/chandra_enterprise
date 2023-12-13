@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>

        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($amc_detail->client->name) }} ({{ ucwords($amc_detail->client->branch_name) }}) <small>AMC Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    <table class="table table-condensed">
                        <thead>
                          
                        </thead>
                        <tbody>
                            <tr>
                                <th style="width: 18%;">Client name : </th>
                                <td>{{ $amc_detail->client->name }}</td>

                                <th style="width: 18%;">Client branch name : </th>
                                <td>{{ $amc_detail->client->branch_name }}</td>
                            </tr>
                            <tr>
                                <th style="width: 18%;">AMC start date : </th>
                                <td>{{ date('d M, Y', strtotime($amc_detail->amc_start_date)) }}</td>

                                <th style="width: 18%;">AMC end date : </th>
                                <td>{{ date('d M, Y', strtotime($amc_detail->amc_end_date)) }}</td>
                            </tr>
                            <tr>
                                <th style="width: 18%;">AMC duration</th>
                                <td>{{ getDuration($amc_detail->amc_duration) }}</td>

                                <th style="width: 18%;">AMC type</th>
                                <td>{{ ucwords($amc_detail->roster->roster_name) }}</td>
                            </tr>
                            <tr>
                                <th style="width: 18%;">AMC total amount</th>
                                <td><i class="fa fa-rupee"></i> {{ $amc_detail->amc_amount }}</td>
  
                                <th style="width: 18%;">Financial year</th>
                                <td>{{ $amc_detail->financial_year }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(isset($client_amc_product))
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Client AMC Product Details
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>AMC product name </th>
                            <th>Amc product model no</th>
                            <th>Amc group name</th>
                          </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($client_amc_product as $key => $amc_product)
                        <tr>
                            <td>{{ $i }}</td>
                            <td><span data-toggle="popover" title="{{ ucwords($amc_product->product->name) }} Detail" data-content= "Product code : {{ $amc_product->product->product_code }} <br>  Serial no : {{ $amc_product->product->serial_no }}<br> Company name: {{ $amc_product->product->company->name }}">{{ $amc_product->product->name }}</span></td>

                             <td>
                                {{ $amc_product->product->model_no }}
                            </td>

                           
                            <td>
                                {{ $amc_product->product->group->name }}
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
    @endif


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Client AMC Transaction Details
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>AMC request date </th>
                            {{-- <th>Amc demand amount</th> --}}
                            <th>Amc demand collected amount</th>
                            <th>Amc demand collected date</th>
                          </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($amc_transaction_details as $key => $amc_transaction_detail)
                        <tr>
                            <td>{{ $i }}</td>
                            <td><span data-toggle="popover" title="{{ ucwords($amc_transaction_detail->amc_month) }} Detail" data-content= "AMC request date : {{ date('d M, Y', strtotime($amc_transaction_detail->amc_rqst_date)) }} <br>  Remarks : {{ $amc_transaction_detail->remarks }}">{{ date('d M, Y', strtotime($amc_transaction_detail->amc_rqst_date)) }}</span></td>

                            {{-- <td>{{ $amc_transaction_detail->amc_demand }}</td> --}}
                           
                            <td>
                                {{ $amc_transaction_detail->amc_demand_collected }}
                            </td>

                           
                            <td>@if($amc_transaction_detail->amc_demand_collected_date != '0000-00-00')
                                {{ $amc_transaction_detail->amc_demand_collected_date }}
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


    @if(Auth::user()->can('view raise-bill'))
    @if($amc_detail->amc_bill->count())
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Client AMC Bill Details
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>AMC bill name </th>
                            <th>Amc bill no</th>
                            <th>Amc bill amount</th>
                            <th>Amc bill date</th>
                            <th>Action</th>
                          </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($amc_detail->amc_bill as $key => $amcbill)
                        <tr>
                            <td>{{ $i }}</td>
                            <td><span data-toggle="popover" title="{{ ucwords($amcbill->bill_name) }} Detail" data-content= "Bill date from : {{ date('d M, Y', strtotime($amcbill->bill_from_date)) }} <br>  Bill date to : {{ date('d M, Y', strtotime($amcbill->bill_to_date)) }}<br> Bill remarks: {{ $amcbill->bill_remarks }} <br> Bill amount paid: {{ $amcbill->amount_paid }} <br> Bill amount paid date: @if($amcbill->paid_on_date != null){{ date('d M, Y', strtotime($amcbill->paid_on_date)) }} @endif <br>  Bill paid remarks : {{ $amcbill->last_follow_up_remarks }}">{{ ucwords($amcbill->bill_name) }}</span></td>

                             <td>
                                {{ $amcbill->bill_no }}
                            </td>
                            
                            <td>{{ $amcbill->bill_amount }}</td>
                           
                            <td>
                                {{ date('d M, Y', strtotime($amcbill->bill_date)) }}
                            </td>

                            <td>
                                <div class="btn-group">

                                    <a href="{{ route('raise-client-amc-bill-payment-details',Crypt::encrypt($amcbill->id)) }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="AMC details" ><i class="fa fa-eye"></i></a>

                                    @if(Auth::user()->can('edit raise-bill'))
                                    <a href="{{ route('raise-client-amc-edit-bill',Crypt::encrypt($amcbill->id)) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit AMC bill"><i class="fa fa-edit"></i></a>
                                    @endif

                                    @if(Auth::user()->can('update bill-payment-details'))
                                    <a href="{{ route('raise-client-amc-edit-bill-payment',Crypt::encrypt($amcbill->id)) }}" class="btn bg-indigo btn-xs" data-toggle="tooltip" title="Update bill payment details"><i class="fa fa-rupee"></i></a>
                                    @endif

                                    @if(Auth::user()->can('delete raise-bill'))
                                    <a href="{{ route('raise-client-amc-delete-bill',Crypt::encrypt($amcbill->id)) }}" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Delete AMC bill" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
                                    @endif

                                </div>
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
    @endif
    @endif

    <div class="col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Assigned Engineer AMC</h2>
            </div>
            <div class="body">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Engineer Name</th>
                            {{-- <th>Engineer Zone</th> --}}
                            <th>Remark</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $assigned_engineers = $amc_detail->assigned_engineers;
                        @endphp
                        @if ($assigned_engineers->count())
                            @foreach ($assigned_engineers as $key => $assigned)
                                <tr>
                                    <td>{{$assigned->engineer->full_name()}} <strong>[{{$assigned->engineer->emp_code}}]</strong></td>
                                    <td>{{$assigned->remark ?? "NA"}}</td>
                                    {{-- <td>Remove Assigned</td> --}}
                                </tr>
                            @endforeach
                        @endif
                        {{-- @if ($all_engineers_belongs_to_client_zone->count())
                            @foreach ($all_engineers_belongs_to_client_zone as $engineer)
                                <tr>
                                    <td>{{$engineer->user->full_name()}} <strong>[{{$engineer->user->emp_code}}]</strong></td>
                                    <td>{{"Auto assigned same zone shared as client."}}</td>
                                </tr>
                            @endforeach
                        @endif --}}
                        @if(!$all_engineers_belongs_to_client_zone->count() && !$assigned_engineers->count())
                            <tr>
                                <td colspan="3" class="text-center text-danger">Engineer not Assigned yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>

<script>
    $('#date_of_transaction').Zebra_DatePicker({
      format: 'd-m-Y',
      // direction: false,
     
    });
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({
            placement: 'top',
            trigger: 'hover',
            html:true
        });
    });
</script>
@stop