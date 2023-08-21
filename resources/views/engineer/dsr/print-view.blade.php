

@extends('layouts.front')


@section('styles')
<style>
 @media print {
  body {
    font-size: 10px;
    line-height: 13px;
    /*width: 100%;*/
  }

  @media print {
  .table-bordered th, .table-bordered td {
    border: 1px solid #000 !important;
  }

  @media print {
  .table td, .table th {
    background-color: #fff !important;
 }

</style>
@stop

@section('content')

<div class="row clearfix">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <div class="card">
      <div class="header">
        <h2>
          DSR of {{ ucwords($dsr->client->name) }}( {{ ucwords($dsr->client->branch_name) }} )   
        </h2>
        <ul class="header-dropdown m-r--5">

          <li><a id="btn" class="btn bg-brown waves-effect"> <i class="fa fa-print" aria-hidden="true"></i> Print </a></li>
  
        </ul>
      </div>


      <div class="body">
        <div id="DivIdToPrint">
          <table class="table table-bordered table-responsive">
         <tr>
        <td colspan="7" rowspan="2" valign="top" ><p><strong>M/s    CHANDRA ENTERPRISES</strong><br>
          An ISO 9001: 2015 CERTIFIED COMPANY<br>
          HOUSE NO &ndash; 27, 1ST FLOOR,    AK AZAD ROAD, REHABARI, GUWAHATI &ndash; 8<br>
          e-mail : <a href="#0">mschandraenterprises@rediffmail.com</a><br>
          Tel : 0361-2733136 (Office)</p></td>
        <td colspan="6" ><p>SCR NO -</p></td>
      </tr>
      <tr>
        <td colspan="6" ><p>COMPLAIN NO - @if($dsr->complaint_id != null){{ $dsr->complaint->complaint_no }}@endif</p></td>
      </tr>
      <tr>
        <td colspan="7" rowspan="4" valign="top" ><p>BANK NAME : {{ ucwords($dsr->client->name) }} </p>
            <p>BRANCH NAME : {{ ucwords($dsr->client->branch_name) }}</p>
          <p>ADDRESS : {{ $dsr->client->address }}</p>
          <br>
            <br></td>
        <td colspan="6" ><p align="center">
            @if( $dsr->maintenance_type == 1)
             BREAK DOWN MAINTENANCE
            @endif
            @if( $dsr->maintenance_type == 2)
             PREVENTIVE MAINTENANCE
            @endif 
          </p>
        </td>

      </tr>
      <tr>
        <td colspan="6" ><p align="center">CALL REPORT</p></td>
      </tr>
      <tr>
        <td colspan="3" ><p align="center">CALL RECEIVED DATE</p></td>
        <td colspan="3" ><p align="center">CALL ATTENDED DATE</p></td>
      </tr>
      <tr>
        <td colspan="3" valign="top" ><p>&nbsp; 
          @if($dsr->call_receive_date != "0000-00-00")
            {{ date('d M, Y', strtotime($dsr->call_receive_date)) }}
            @endif
          </p>
        </td>
        <td colspan="3" valign="top" ><p>&nbsp;
            @if($dsr->call_attend_date != "0000-00-00")
            {{ date('d M, Y', strtotime($dsr->call_attend_date)) }}
            @endif 
          </p>
        </td>
      </tr>
      <tr>
        <td colspan="7" ><p>CONTACT PERSON : {{ ucwords($dsr->contact_person_name) }}</p></td>
        <td colspan="6" ><p>TEL NO. : {{ $dsr->contact_person_ph_no }} </p></td>
      </tr>
      <tr>
        <td colspan="3" ><p>PRODUCT : @if(isset($dsr->product_id)){{ ucwords($dsr->product->name) }}@endif</p></td>
        <td colspan="10" rowspan="2" ><p>1. @if(isset($dsr->product_id)) {{ $dsr->product->serial_no }} @endif</p>
            </td>
      </tr>
      <tr>
        <td colspan="3" ><p>MODEL : @if(isset($dsr->product_id)) {{ ucwords($dsr->product->model_no) }} @endif</p></td>
      </tr>
      <tr>
        <td colspan="13" ><p>NATURE OF COMPLAIN (BY CUSTOMER) &ndash; {{ $dsr->nature_of_complaint_by_customer }}</p>
            <p></p>
          <p>FAULT OBSERVED (BY ENGINEER) &ndash; {{ $dsr->fault_observation_by_engineer }}</p>
          <p></p>
          <p>ACTION TAKEN &amp; RESULT (BY ENGINEER) &ndash; {{ $dsr->action_taken_by_engineer }}</p>
          <p></p>
          <p>REMARKS IF ANY &ndash; {{ $dsr->remarks }}</p>
          <p></p>
        </td>
      </tr>
      <tr>
        <td colspan="5" ><p align="center"><strong>PARTS SUPPLIED/ REPLACED</strong></p></td>
        <td colspan="3" ><p align="center"><strong>WORN OUT PART TAKEN BACK</strong></p></td>
        <td colspan="3" ><p align="center"><strong>UNIT PRICE</strong></p></td>
        <td colspan="2" ><p align="center"><strong>LABOUR</strong></p></td>
      </tr>
      <tr>
        <td width="5%" ><p align="center">SL NO</p></td>
        <td width="6%" ><p align="center">PART NO</p></td>
        <td colspan="2" ><p align="center">DESCRIPTION</p></td>
        <td width="6%" ><p align="center">QNTY</p></td>
        <td width="8%" ><p align="center">YES/ NO</p></td>
        <td colspan="2" ><p align="center">QNTY</p></td>
        <td width="8%" ><p align="center">FREE</p></td>
        <td colspan="2" ><p align="center">CHARGEABLE</p></td>
        <td width="6%" ><p align="center">FREE</p></td>
        <td width="11%" ><p align="center">CHARGEABLE</p></td>
      </tr>

      @if(isset($dsr->dsr_transaction))
      @php $i=1 @endphp
      @foreach($dsr->dsr_transaction as $dsr_trans)
      <tr>
        <td valign="top" ><p>{{ $i }}</p></td>
        <td valign="top" ><p>{{ $dsr_trans->spare_part->part_no }}</p></td>
        <td colspan="2" valign="top" ><p> {{ ucwords($dsr_trans->spare_part->name) }}</p></td>
        <td valign="top" ><p> {{ $dsr_trans->spare_part_quantity }}</p></td>
        <td valign="top" ><p> @if($dsr_trans->spare_part_taken_back == 1)Yes @endif @if($dsr_trans->spare_part_taken_back == 0)No @endif </p></td>
        <td colspan="2" valign="top" ><p> {{  $dsr_trans->spare_part_taken_back_quantity }} </p></td>
        <td valign="top" ><p> {{ $dsr_trans->unit_price_free }} </p></td>
        <td colspan="2" valign="top" ><p> {{ $dsr_trans->unit_price_chargeable }} </p></td>
        <td valign="top" ><p> @if($dsr_trans->labour_free == 1) <i class="fa fa-check"></i> @endif</p></td>
        <td valign="top" ><p> @if($dsr_trans->labour_free == 0) <i class="fa fa-check"></i> @endif </p></td>
      </tr>
      @php $i++ @endphp
      @endforeach
      @endif
     
      <tr>
        <td colspan="13" ><p align="center"><strong>MACHINE TESTED WORKING SATISFACTORY</strong></p></td>
      </tr>
      <tr>
        <td colspan="6" valign="top" ><p>ENGINEER NAME &ndash; {{ ucwords($dsr->engineer->first_name.' '.$dsr->engineer->middle_name.' '.$dsr->engineer->last_name) }} </p></td>
        <td colspan="7" rowspan="3" valign="bottom" ><p align="center">CUSTOMER SIGNATURE WITH DATE &amp; RUBBER STAMP</p></td>
      </tr>
      <tr>
        <td colspan="6" valign="top" ><p>DATE &ndash; </p></td>
      </tr>
      <tr>
        <td height="101" colspan="6" valign="top" ><p>&nbsp;</p>
          <p>&nbsp;</p>
          <p align="right">ENGINEER SIGNATURE</p></td>
      </tr>
    </table>
     
        </div>
      </div>

    </div>

  </div>
</div>

@endsection


@section('scripts')
<script>

function printData()
{
   var divToPrint=document.getElementById("DivIdToPrint");
   newWin= window.open("");
   newWin.document.write("<html>");
   newWin.document.write("<head>");
   newWin.document.write(`<link media="all" rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" type="text/css"/>`);
   newWin.document.write("<style>");
   newWin.document.write("@media print {body {font-size: 6px; line-height: 8px;} @media print { .table-bordered th, .table-bordered td { border: 1px solid #000 !important;} @media print { .table td, .table th { font-size:10px; line-height:12px; background-color: #fff !important; padding:4px; }");
   newWin.document.write("</style>");
   newWin.document.write("</head>");
   newWin.document.write("<body>");
   newWin.document.write(divToPrint.outerHTML);
   newWin.document.write("</body>");
   // newWin.document.write(divToPrint.innerHTML);
   // newWin.document.write('<html><link media="all" rel="stylesheet" href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" type="text/css" /></html>');
   newWin.print();
   newWin.close();
}

$('#btn').on('click',function(){
  printData();
})


// function printDiv() 
// {

//   var divToPrint=document.getElementById('DivIdToPrint');

//   var newWin=window.open('','Print-Window');

  

//   newWin.document.open();

//   newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

//   newWin.document.close();

//   newWin.document.write('<link rel="stylesheet" href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" type="text/css" />');

//   setTimeout(function(){newWin.close();},10);

// }
</script>
@stop
