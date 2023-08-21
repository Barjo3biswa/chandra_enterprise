@extends('layouts.front')


@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Zebra_datepicker/1.9.12/css/bootstrap/zebra_datepicker.min.css">
<style>
    .form-group .form-line .form-label {
        top: -10px!important;
        font-size: 12px !important;
    }
</style>
@stop

@section('content')

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Stock In Form <small>Update stock-ins</small>
                </h2>
                <ul class="header-dropdown m-r--5">
                  @if(Auth::user()->can('view spare-part-stock-in'))
                  <li><a href="{{ route('view-all-stock-in') }}" class="btn btn-success">View all</a></li>
                  @endif
                </ul>
            </div>
            <div class="body">
                <div class="body table-responsive">
                    
  
                <form action="{{ route('update-stock-in',Crypt::encrypt($sp_master->id)) }}" method="post" id="form_validation">
                    {{ csrf_field() }}
                    {!! method_field('PATCH') !!}

                    

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="date_of_transaction" name="date_of_transaction" data-zdp_readonly_element="false" value="{{ old('date_of_transaction',date('d-m-Y', strtotime($sp_master->date_of_transaction))) }}" required>
                                    <label class="form-label">Date of transaction eg,(dd-mm-yyyy)</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="purchase_from" value="{{ old('purchase_from',$sp_master->purchased_from) }}">
                                    <label class="form-label">Purchased from</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="remarks" value="{{ old('remarks',$sp_master->remarks) }}">
                                    <label class="form-label">Remarks</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <h4>Add spare parts</h4><hr><br>
                    
                <div id="main_sp_parent" class="main_sp_parent">
                    {{-- <div  class="sub_sp_parent"> --}}
                @foreach($sp_transactions as $key =>  $sp_transaction)
                    <div class="row sub_sp_parent" id="sub_sp_parent">
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="sp_name[]" required="required">
                                        <option value=""> Please select a Spare part </option>
                                        <?php foreach ($spare_parts as $spare_part): ?>
                                        <option value="{{ $spare_part->id }}" data-themeid="{{ $spare_part->id }}" {{ old('sp_name',$sp_transaction->spare_parts_id) == "$spare_part->id" ? 'selected' : '' }}>{{ ucwords($spare_part->name) }} ({{ $spare_part->part_no }})</option>
                                        <?php endforeach; ?>
                                    </select>
                                    {{-- <label class="form-label">Select Group</label> --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                   <input type="text" class="form-control" name="purchase_quantity[]" min="0" onkeyup="this.value=this.value.replace(/[^0-9 -]/g,'')" maxlength="3" required="required" value="{{ old('purchase_quantity',$sp_transaction->purchase_quantity) }}">
                                   <label class="form-label">Purchase quantity</label>
                               </div>
                           </div>
                        </div>

                        <div class="col-md-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm add_new_ap" data-toggle="tooltip" title="Add new spare part"><i class="fa fa-plus"></i></button>

                                <button type="button" class="btn btn-danger btn-sm remove_ap"><i class="fa fa-trash"></i></button>
                            </div>
                         </div>

                    </div>
                    @endforeach
                </div>
                {{-- </div> --}}

                

                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
                    </div>
                </div>

                </form>


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

  // $('.add_new_ap').on('click', function() { 
    $('.main_sp_parent').on('click', '.add_new_ap', function() {
        // $.each($('.main_sp_parent').find('.sub_sp_parent'), function(index, element){
        //  console.log($(element).find('#sp_name').val());
        // });
        $('.main_sp_parent').append('<div class="sub_sp_parent"><div class="row" id="sub_sp_parent"><div class="col-md-3"><div class="form-group form-float"><div class="form-line"><select class="form-control show-tick required" name="sp_name[]" id="sp_name" required="required"><option value=""> Please select a Spare part </option><?php foreach ($spare_parts as $spare_part): ?><option value="{{ $spare_part->id }}" data-themeid="{{ $spare_part->id }}" {{ old('sp_name') == "$spare_part->id" ? 'selected' : '' }}>{{ ucwords($spare_part->name) }}</option><?php endforeach; ?></select></div></div></div><div class="col-md-3"><div class="form-group form-float"><div class="form-line"><input type="text" class="form-control" name="purchase_quantity[]" min="0" onkeyup="" maxlength="3" placeholder="Purchase quantity" required="required"></div></div></div><div class="col-md-3"><div class="btn-group"><button type="button" class="btn btn-success btn-sm add_new_ap" data-toggle="tooltip" title="Add new spare part"><i class="fa fa-plus"></i></button> <button type="button" class="btn btn-danger btn-sm remove_ap"><i class="fa fa-trash"></i></button> </div></div></div></div>');
        // return false; //prevent form submission
   });

   $('.main_sp_parent').on('click', '.remove_ap', function() {
       // $('.sub_sp_parent').children("div").last().remove();
    if ($(document).find('.sub_sp_parent').length==1)
    {
        alert('Atleast one spare part in the stockin list');
        return false;
    }
    $(this).parents('.sub_sp_parent').slideUp(500,function(){
        $(this).remove();
    })
   });
</script>
@stop