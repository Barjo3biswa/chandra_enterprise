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
                    {{ ucwords($spare_part->name) }} <small>Details</small>
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
                                <th style="width: 15%;">Name : </th>
                                <td>{{ ucwords($spare_part->name) }}</td>

                                <th style="width: 15%;">Part no : </th>
                                <td>{{ $spare_part->part_no }}</td>

                                <th style="width: 15%;">Code : </th>
                                <td>{{ $spare_part->sp_code }}</td>
                            </tr>

                            <tr>
                                <th style="width: 15%;">Group name : </th>
                                @if($spare_part->group_id != null)
                                <td>{{ ucwords($spare_part->group->name) }}</td>
                                @endif

                                <th style="width: 15%;">Subgroup name : </th>
                                @if($spare_part->subgroup_id != null)
                                <td>{{ ucwords($spare_part->subgroup->name) }}</td>
                                @endif

                                <th></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th style="width: 15%;">Company name : </th>
                                @if($spare_part->company_id != null)
                                <td>{{ ucwords($spare_part->company->name) }}</td>
                                @endif

                                <th style="width: 15%;">Brand name : </th>
                                <td>{{ ucwords($spare_part->brand) }}</td>

                                <th></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th style="width: 15%;">Opening balance : </th>
                                <td>{{ $spare_part->opening_balance }}</td>

                                <th style="width: 15%;">With effect from : </th>
                                @if($spare_part->with_effect_from != '0000-00-00')
                                <td>{{ date('d M, Y', strtotime($spare_part->with_effect_from)) }}</td>
                                @endif

                                <th></th>
                                <td></td>
                            </tr>

                            <tr>
                              
                                <th style="width: 15%;">Transaction date : </th>
                                @if($spare_part->transaction_date != '0000-00-00')
                                <td>{{ date('d M, Y', strtotime($spare_part->transaction_date)) }}</td>
                                @endif

                                <th> </th>
                                <td></td> 

                                <th></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th scope="row" style="width: 20%;">Technical specification : </th>
                                <td>{{ $spare_part->tech_specification }}</td>

                                <th></th>
                                <td></td>

                                <th></th>
                                <td></td>
                            </tr>

                            <tr>
                                <th scope="row" style="width: 20%;">Remarks : </th>
                                <td>{{ $spare_part->remarks }}</td>

                                <th></th>
                                <td></td>

                                <th></th>
                                <td></td>
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
                    {{ ucwords($spare_part->name) }} <small>Transaction Details</small>
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Purchase quantity</th>
                            <th>Issued quantity</th>
                            <th>Last transaction by</th>
                            <th>Transaction date </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($spare_part_transactions as $spare_part_transaction)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $spare_part_transaction->description }}</td>
                            <td>{{ $spare_part_transaction->purchase_quantity }}</td>
                            <td>{{ $spare_part_transaction->issued_quantity }}</td>
                            <td>{{ ucwords($spare_part_transaction->user->first_name.' '.$spare_part_transaction->user->middle_name.' '.$spare_part_transaction->user->last_name) }}</td>
                            <td>
                                @if($spare_part_transaction->transaction_date != "0000-00-00")
                                {{ date('d M, Y', strtotime($spare_part_transaction->transaction_date)) }}
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

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/zebra_datepicker.min.js"></script>

<script>
    $('#date_of_transaction').Zebra_DatePicker({
      format: 'd-m-Y',
      direction: false,
     
    });
</script>
@stop