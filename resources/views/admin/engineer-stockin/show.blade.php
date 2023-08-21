@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    Issued Stock 
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
                                <th style="width: 18%;">Date of transaction : </th>
                                <td>{{ date('d M, Y', strtotime($sp_master->date_of_transaction)) }}</td>

                                <th style="width: 18%;">Engineer name : </th>
                                <td>{{ ucwords($sp_master->user->first_name.' '.$sp_master->user->middle_name.' '.$sp_master->user->last_name) }}</td>
                            </tr>

                            <tr>
                               <th style="width: 18%;">Remarks : </th>
                               <td>{{ ucwords($sp_master->remarks) }}</td>

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
                    Issued Stock Transaction Details
                </h2>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    <table class="table table-condensed">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Spare part name</th>
                            <th>Issued quantity</th>
                            <th>Supplied quantity for dsr</th>
                            <th>Transaction date </th>
                         </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($sp_transaction as $key => $spare_part_transaction)
                        <tr>
                            <td>{{ $i }}</td>

                            <td>{{ ucwords($spare_part_transaction->spare_part->name) }}</td>
                            <td>{{ $spare_part_transaction->stock_in }}</td>
                            <td>{{ $spare_part_transaction->stock_out }}</td>

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