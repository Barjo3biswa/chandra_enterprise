@extends('layouts.front')


@section('styles')
<style>
    .no_style a{
        text-decoration: none!important;
        color: #fff!important;
        font-weight: bold!important;
        font-size: 15px!important;
    }
</style>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <a href="{{ url()->previous() }}" class="btn bg-blue-grey waves-effect"> <i class="fa fa-arrow-left"></i> Back</a>
        
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                     Details
                </h2>

                <ul class="header-dropdown m-r--5">
                <li>
                    <a href="{{ route('print-view-dsr', Crypt::encrypt($dsr->id)) }}" target="_blank" class="btn bg-lime waves-effect btn-sm"><i class="fa fa-print"></i> Print</a>
                </li>
                
            </ul>

                

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

                                <th scope="row" style="width: 18%;">Product name : </th>
                                @if($dsr->product_id != null)
                                <td>{{ ucwords($dsr->product->name) }}</td>
                                @endif
                            </tr>

                            @if($dsr->complaint_id != null)
                            <tr>
                                <th scope="row" style="width: 18%;">Complaint no : </th>
                                <td>{{ $dsr->complaint->complaint_no }}</td>

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

                            @if($dsr->product_id != null)
                            <tr>
                            	<th scope="row" style="width: 18%;">Product Model no : </th>
                                <td>{{ $dsr->product->model_no }}</td>

                                <th scope="row" style="width: 18%;">Equipment no : </th>
                                <td>{{ ucwords($dsr->product->equipment_no) }}</td>
                            </tr>
                            @endif 

                            <tr>
                            	<th scope="row" style="width: 18%;">Group name : </th>
                                @if($dsr->group_id != null)
                                <td>{{ ucwords($dsr->product->group->name) }}</td>
                                @endif

                                <th scope="row" style="width: 18%;">Dsr entry date and time : </th>
                                <td>{{ date('d M, Y h:i A', strtotime($dsr->entry_datetime)) }}</td>
                             </tr>

                            <tr>
                                <th>Nature of complaint by customer : </th>
                                <td>{{ $dsr->nature_of_complaint_by_customer }}</td>

                                <th scope="row" style="width: 18%;"></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Fault observed by engineer : </th>
                                <td>{{ $dsr->fault_observation_by_engineer }}</td>

                                <th scope="row" style="width: 18%;"></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Action taken & result by engineer : </th>
                                <td>{{ $dsr->action_taken_by_engineer }}</td>

                                <th scope="row" style="width: 18%;"></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Remarks : </th>
                                <td>{{ $dsr->remarks }}</td>

                                <th scope="row" style="width: 18%;"></th>
                                <td></td>
                            </tr>
                           
                        </tbody>
                    </table>
                    </div>
                </div>

            @if(isset($dsr->dsr_transaction))
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
                                        <span data-toggle="popover" title="{{ ucwords($dsr_trans->spare_part->name) }} Detail" data-content= "Stock in hand : {{ $dsr_trans->spare_part_stock_in_hand }} <br> Spare part taken back : @if($dsr_trans->spare_part_taken_back == 1)Yes @endif @if($dsr_trans->spare_part_taken_back == 0)No @endif <br> Spare part taken back quantity : {{  $dsr_trans->spare_part_taken_back_quantity }} <br>  Unit price free : {{ $dsr_trans->unit_price_free }} <br> Unit price chargeable : {{ $dsr_trans->unit_price_chargeable }} <br> Labout free : @if($dsr_trans->labour_free == 1)Yes @endif @if($dsr_trans->labour_free == 0)No @endif ">

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