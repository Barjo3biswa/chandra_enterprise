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
                                
                                <th style="width: 18%;">Plan</th>
                                <td>{{ $amc_detail->roster->roster_name }}</td>

                                
                            </tr>
                            <tr>

                                <th style="width: 18%;">Financial year</th>
                                <td>{{ $amc_detail->financial_year }}</td>

                                <th style="width: 18%;">AMC total amount</th>
                                <td><i class="fa fa-rupee"></i> {{ $amc_detail->amc_amount }}</td>

                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                   AMC Product Details
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    
                <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>AMC product name </th>
                            <th>Group name</th>
                            <th>Part no</th>
                            <th>Serial no</th>
                            <th>Manufacture date</th>
                            <th>Date of installation</th>
                          </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($amc_detail->amc_master_product as $key => $amc_product_detail)
                        <tr>
                            <td>{{ $i }}</td>
                            <td><span data-toggle="popover" title="{{ ucwords($amc_product_detail->product->name) }} Detail" data-content= "Product code : {{ $amc_product_detail->product->product_code }} <br>  Brand : {{ $amc_product_detail->product->brand }} <br> Equipment no : {{ $amc_product_detail->product->equipment_no }} <br> Warrenty : {{ $amc_product_detail->product->warranty }}@if($amc_product_detail->product->warranty != null) years @endif">{{ ucwords($amc_product_detail->product->name) }}</span></td>

                            <td>@if(isset($amc_product_detail->group_id)){{ $amc_product_detail->product->group->name }}@endif</td>

                            <td>{{ $amc_product_detail->product->part_no }}</td>
                           
                            <td>
                                {{ $amc_product_detail->product->serial_no }}
                            </td>

                           
                            <td>
                                @if($amc_product_detail->product->manufacture_date != '0000-00-00')
                                {{ date('d M, Y', strtotime($amc_product_detail->product->manufacture_date)) }}
                                @endif
                            </td>

                            <td>
                                @if($amc_product_detail->product->date_of_purchase != '0000-00-00')
                                {{ date('d M, Y', strtotime($amc_product_detail->product->date_of_purchase)) }}
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



    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    AMC Transaction Details
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
                        @foreach($amc_detail->amc_master_transaction as $key => $amc_transaction_detail)
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
                            <td><span data-toggle="popover" title="{{ ucwords($amcbill->bill_name) }} Detail" data-content= "Bill date from : {{ date('d M, Y', strtotime($amcbill->bill_from_date)) }} <br>  Bill date to : {{ date('d M, Y', strtotime($amcbill->bill_to_date)) }}<br> Bill remarks: {{ $amcbill->bill_remarks }} <br> Bill amount paid: {{ $amcbill->amount_paid }} <br> Bill amount paid date: @if($amcbill->paid_on_date != null){{ date('d M, Y', strtotime($amcbill->paid_on_date)) }} @endif <br>  Bill paid remarks : {{ $amcbill->last_follow_up_remarks }} <br>  Last bill followup by : {{ ucwords($amcbill->user->first_name.' '.$amcbill->user->middle_name.' '.$amcbill->user->last_name) }}">{{ ucwords($amcbill->bill_name) }}</span></td>

                             <td>
                                {{ $amcbill->bill_no }}
                            </td>
                            
                            <td>{{ $amcbill->bill_amount }}</td>
                           
                            <td>
                                {{ date('d M, Y', strtotime($amcbill->bill_date)) }}
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('amc-bill-payment-details',Crypt::encrypt($amcbill->id)) }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="AMC details" ><i class="fa fa-eye"></i></a>

                                    @if((date('Y-m-d', strtotime($amcbill->last_follow_up_date))) == (date('Y-m-d')))
                                    <a href="{{ route('raise-amc-edit-bill',Crypt::encrypt($amcbill->id)) }}" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit AMC bill"><i class="fa fa-edit"></i></a>
                                    @endif

                                    <a href="{{ route('amc-create-bill-payment',Crypt::encrypt($amcbill->id)) }}" class="btn bg-indigo btn-xs" data-toggle="tooltip" title="Add payment details"><i class="fa fa-rupee"></i></a>

                                    @if((date('Y-m-d', strtotime($amcbill->last_follow_up_date))) == (date('Y-m-d')))
                                    <a href="{{ route('amc-payment-delete-bill',Crypt::encrypt($amcbill->id)) }}" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Delete payment" onclick="return confirm('Are you sure')"><i class="fa fa-trash"></i></a>
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