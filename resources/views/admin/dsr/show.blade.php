@extends('layouts.front')


@section('styles')
<style>
    .no_style a {
        text-decoration: none !important;
        color: #fff !important;
        font-weight: bold !important;
        font-size: 15px !important;
    }
</style>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i>
            Back</a>

        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    {{ ucwords($dsr->client->name) }} ({{ ucwords($dsr->client->branch_name) }})DSR Details <small>By
                        {{ ucwords($dsr->engineer->first_name.' '.$dsr->engineer->middle_name.' '.$dsr->engineer->last_name) }}</small>
                </h2>

                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="{{ route('service-report-print-view-dsr', Crypt::encrypt($dsr->id)) }}" target="_blank"
                            class="btn bg-lime waves-effect btn-sm"><i class="fa fa-print"></i> Print</a>
                    </li>

            </div>
            <div class="body">
                <div class="body table-responsive">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-condensed">
                                <thead>
                                    <!--<tr>
                                        <th></th>
                                        <th></th>
                                    </tr>-->
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 18%;">Scr No : </th>
                                        <td>{{ $dsr->scr_no }}</td>

                                        <th scope="row" style="width: 10%;"></th>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 18%;">Client Name : </th>
                                        <td>{{ ucwords($dsr->client->name) }}</td>

                                        <th scope="row" style="width: 10%;">Branch name : </th>
                                        <td>{{ ucwords($dsr->client->branch_name) }}</td>

                                    </tr>
                                    <tr>
                                        <th scope="row" style="width: 18%;">Contact person name : </th>
                                        <td>{{ ucwords($dsr->contact_person_name) }}</td>

                                        <th scope="row" style="width: 18%;">contact person ph no : </th>
                                        <td>{{ $dsr->contact_person_ph_no }}</td>
                                    </tr>
                                    <tr>


                                        <th scope="row" style="width: 18%;">Call receive date : </th>
                                        <td>
                                            @if($dsr->call_receive_date != "0000-00-00")
                                            {{ date('d M, Y', strtotime($dsr->call_receive_date)) }}
                                            @endif
                                        </td>

                                        <th scope="row" style="width: 18%;">Call attend date : </th>
                                        <td>
                                            @if($dsr->call_attend_date != "0000-00-00")
                                            {{ date('d M, Y', strtotime($dsr->call_attend_date)) }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row" style="width: 18%;">Maintenance type : </th>
                                        <td>
                                            @if( $dsr->maintenance_type == 1)
                                            Break Down Maintenance
                                            @endif
                                            @if( $dsr->maintenance_type == 2)
                                            Preventive Maintenance
                                            @endif
                                        </td>

                                        <th scope="row" style="width: 18%;">Dsr entry date and time : </th>
                                        <td>{{ date('d M, Y h:i A', strtotime($dsr->entry_datetime)) }}</td>

                                    </tr>


                                    @if(isset($dsr->complaint_id))
                                    <tr>
                                        <th scope="row" style="width: 18%;">Complaint no : </th>
                                        <td>{{ $dsr->complaint->complaint_no ?? "NA" }}</td>

                                        <th scope="row" style="width: 18%;">Complaint status : </th>
                                        <td>
                                            @if($dsr->complaint_status == 2)
                                            Under process
                                            @endif
                                            @if($dsr->complaint_status == 3)
                                            Closed
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(count($dsr->dsr_transaction) > 0)
                    <h4>Spare part details</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">

                                <table class="table table-condensed">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Spare part name</th>
                                            <th>Spare part no</th>
                                            <th>Supplied quantity</th>
                                            <th>Stock in hand</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($dsr->dsr_transaction as $dsr_trans)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                <span data-toggle="popover"
                                                    title="{{ ucwords($dsr_trans->spare_part->name) }} Detail"
                                                    data-content="Stock in hand : {{ $dsr_trans->spare_part_stock_in_hand }} <br> Spare part taken back : @if($dsr_trans->spare_part_taken_back == 1)Yes @endif @if($dsr_trans->spare_part_taken_back == 0)No @endif <br> Spare part taken back quantity : {{  $dsr_trans->spare_part_taken_back_quantity }} <br>  Unit price free : {{ $dsr_trans->unit_price_free }} <br> Unit price chargeable : {{ $dsr_trans->unit_price_chargeable }} <br> Labout free : @if($dsr_trans->labour_free == 1)Yes @endif @if($dsr_trans->labour_free == 0)No @endif ">

                                                    {{ $dsr_trans->spare_part->name }}
                                                </span>
                                            </td>
                                            <td>{{ $dsr_trans->spare_part->part_no }}</td>
                                            <td>{{ $dsr_trans->spare_part_quantity }}</td>
                                            <td>{{ $dsr_trans->spare_part_stock_in_hand }}</td>
                                        </tr>
                                        @php $i++ @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="body table-responsive">
                <h3>Products List</h3>
                <table class="table table-bordered" style="font-size: 13px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Model No</th>
                            <th>Serial No</th>
                            <th>Product Group</th>
                            <th>Nature Of Complaint (Customer)</th>
                            <th>Fault Observation (Engineer)</th>
                            <th>Action Taken (Engineer)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($dsr->dsr_products->count())
                            @foreach ($dsr->dsr_products as $index => $dsr_product)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$dsr_product->product->name}}</td>
                                <td>{{$dsr_product->model_no ?? "NA"}}</td>
                                <td>{{$dsr_product->serial_no ?? "NA"}}</td>
                                <td>{{$dsr_product->group->name ?? "NA"}}</td>
                                <td>{{$dsr_product->nature_of_complaint_by_customer ?? "NA"}}</td>
                                <td>{{$dsr_product->fault_observation_by_engineer ?? "NA"}}</td>
                                <td>{{$dsr_product->action_taken_by_engineer ?? "NA"}}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-danger text-center">No product records found</td>
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
<script>
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover({
            placement: 'top',
            trigger: 'hover',
            html:true
        });
    });
</script>
@stop