<style>
    .bg-success{
        background: #A9D18D;
    }
</style>
@php
    $financial_year = collect($financial_year);
    $counter = 1;
    if(!function_exists("returnQuarterData")){
        function returnQuarterData($dsr_grouped, $engineer_id, $client_id, $quarter, $product_id)  {
            $months = generateMonthFromQuarter($quarter);
            if(!isset($dsr_grouped[$engineer_id])){
                return [];
            }
            if(!isset($dsr_grouped[$engineer_id][$client_id])){
                return [];
            }
            foreach($months as $month){
                if(isset($dsr_grouped[$engineer_id][$client_id][$month])){
                    // if($product_id == 1227){
                    //     dump($product_id);
                    //     dump($dsr_grouped[$engineer_id][$client_id][$month]);
                    //     foreach ($dsr_grouped[$engineer_id][$client_id][$month] as $key => $daily_service_reports) {
                    //         dump($daily_service_reports->dsr_products->where('product_id',"=", $product_id));
                    //         if($daily_service_reports->dsr_products->where('product_id',"=", $product_id)){
                    //             break;
                    //         }
                    //     }
                    // }
                    // if($product_id == 1227){
                    //     dd("HALT HERE");
                    // }
                    foreach ($dsr_grouped[$engineer_id][$client_id][$month] as $key => $daily_service_reports) {
                        if($daily_service_reports->dsr_products->where('product_id',"=", $product_id)->count()){
                            return $daily_service_reports;
                            break;
                        }
                    }
                    return [];
                }
            }
            return [];
        }
    }
    if(!function_exists("generateMonthFromQuarter")){
        function generateMonthFromQuarter ($quarter){
            // first quarter
            if($quarter == 1){
                return ["04", "05", "06"];
            }elseif($quarter == 2){
                return ["07", "08", "09"];
            }elseif($quarter == 3){
                return ["10", "11", "12"];
            }elseif($quarter == 4){
                return ["01", "02", "03"];
            }

        }
    }
    if(!function_exists("generateQuarterData")){
        // function used for genearet quarter data by
        // comparing date month and dsr on same date.
        function generateQuarterData($data, $quarter)
        {
            $data_array = [];
            $months = generateMonthFromQuarter($quarter);
            $data_array["ENG Name {$quarter}qtr"] = "";
            $data_array["SCR No {$quarter}qtr"]   = "";
            foreach($months as $month ){
                $month_name = date('M', strtotime("2019-".$month."-01"));
                if($data){
                    $data_array["ENG Name {$quarter}qtr"] = $data->engineer->full_name();
                    $data_array["SCR No {$quarter}qtr"]   = $data->scr_no;
                    if(dateFormat($data->entry_datetime, "m") == $month){
                        $data_array["$month_name".date("y")]   = dateFormat($data->entry_datetime, "d-m-Y");
                    }else{
                        $data_array["$month_name".date("y")]   = "";
                    }
                }else{
                    $data_array["$month_name".date("y")]   = "";
                }
            }
            return $data_array;
        }
    }
    if(!function_exists("findAmcAmount")){
        function findAmcAmount($amc_records_belongs_to_client, $product, $quarter, $financial_year){
            $amount  = 0.00;
            $amc_amount = 0.0;
            $date_according_to_month = "";
            $months             = generateMonthFromQuarter($quarter);
            $date_from_string   = date("Y", strtotime($financial_year["second"]))."-".min($months);
            $date_to_string     = date("Y", strtotime($financial_year["second"]))."-".max($months);
            $date_from          = $date_from_string."-01";
            $date_to            = date("Y-m-t", strtotime($date_to_string."-01"));
            // dump($amc_records_belongs_to_client);
            // dd($product->product_id);
            if($amc_records_belongs_to_client){
                foreach($amc_records_belongs_to_client as $client_record){
                    if(!$client_record->amc_master_product->where("product_id", $product->product_id)->count()){
                        continue;
                    }
                    $amc_amount = $client_record->amc_amount;
                    $filterred = $client_record->amc_master_transaction->filter(function($item) use ($date_from, $date_to){
                        return strtotime($item->amc_rqst_date) >= strtotime($date_from) && strtotime($item->amc_rqst_date) <= strtotime($date_to);
                    })->all();
                    // show only if product is included in the amc
                    if($filterred){
                        $amount = collect($filterred)->first()->amc_demand;
                    }
                    if($amount == 0){
                        $amount = $amc_amount;
                    }
                    break;
                }
            }
            return $amount;
        }
    }
@endphp
<table border='1' style="border-collapse: collapse; border:1px solid black;">
    <tbody>
        <tr>
            <th colspan="31" style="text-align: center; border:1px solid black;"><strong>{{$assigneds->first()->zone->name}} - {{$assigneds->first()->user->full_name()}}</strong></th>
        </tr>
        <tr>
            <th style="border:1px solid black;" rowspan="2">SL NO</th>
            <th style="border:1px solid black;" rowspan="2">Bank Name</th>
            <th style="border:1px solid black;" rowspan="2">Branch Name</th>
            <th style="border:1px solid black;" rowspan="2">Product</th>
            <th style="border:1px solid black;" rowspan="2">Date of Inst.</th>
            <th style="border:1px solid black;" rowspan="2">BRAND</th>
            <th style="border:1px solid black;" rowspan="2">MODEL</th>
            <th style="border:1px solid black;" rowspan="2">No. of Machine</th>
            <th style="border:1px solid black;" rowspan="2" width="14">New M/c Sl No. 1</th>
            <th style="border:1px solid black;" rowspan="2" width="14">New M/c Sl No. 2</th>
            <th style="border:1px solid black;" rowspan="2" width="14">Contact No.</th>

            <th  colspan="6" class="bg-success" style="text-align: center; border:1px solid black;">4th Qtr PM</th>
            <th  colspan="6" class="bg-success" style="text-align: center; border:1px solid black;">3rd Qtr PM</th>
            <th  colspan="6" class="bg-success" style="text-align: center; border:1px solid black;">2nd Qtr PM</th>
            <th  colspan="6" class="bg-success" style="text-align: center; border:1px solid black;">1st Qtr PM</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="border:1px solid black;" class="bg-success" >Amount</th>
            <th style="border:1px solid black;" class="bg-success" >ENG Name</th>
            <th style="border:1px solid black;" class="bg-success" >SCR NO</th>
            <th style="border:1px solid black;" class="bg-success" >Jan'{{dateFormat($financial_year->last(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Feb'{{dateFormat($financial_year->last(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Mar'{{dateFormat($financial_year->last(), "y")}}</th>

            <th style="border:1px solid black;" class="bg-success" >Amount</th>
            <th style="border:1px solid black;" class="bg-success" >ENG Name</th>
            <th style="border:1px solid black;" class="bg-success" >SCR NO</th>
            <th style="border:1px solid black;" class="bg-success" >Oct'{{dateFormat($financial_year->first(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Nov'{{dateFormat($financial_year->first(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Dec'{{dateFormat($financial_year->first(), "y")}}</th>

            <th style="border:1px solid black;" class="bg-success" >Amount</th>
            <th style="border:1px solid black;" class="bg-success" >ENG Name</th>
            <th style="border:1px solid black;" class="bg-success" >SCR NO</th>
            <th style="border:1px solid black;" class="bg-success" >Jul'{{dateFormat($financial_year->first(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Aug'{{dateFormat($financial_year->first(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Sept'{{dateFormat($financial_year->first(), "y")}}</th>

            <th style="border:1px solid black;" class="bg-success" >Amount</th>
            <th style="border:1px solid black;" class="bg-success" >ENG Name</th>
            <th style="border:1px solid black;" class="bg-success" >SCR NO</th>
            <th style="border:1px solid black;" class="bg-success" >Apr'{{dateFormat($financial_year->first(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >May'{{dateFormat($financial_year->first(), "y")}}</th>
            <th style="border:1px solid black;" class="bg-success" >Jun'{{dateFormat($financial_year->first(), "y")}}</th>
        </tr>
        @foreach ($assigneds as $assigned )
            @foreach ($assigned->client->assigned_products as $product_index => $product )
                <tr>
                    @if($product_index == 0)
                        <td style="border:1px solid black;">{{$counter}}</td>
                        <td style="border:1px solid black;">{{$assigned->client->name}}</td>
                        <td style="border:1px solid black;">{{$assigned->client->branch_name}}</td>
                        <td style="border:1px solid black;">{{$product->product->name}}</td>
                        <td style="border:1px solid black;">{{$product->date_of_install ?? "--"}}</td>
                        <td style="border:1px solid black;">{{$product->product->brand->name ?? "--"}}</td>
                        <td style="border:1px solid black;">{{$product->product->model_no ?? "--"}}</td>
                        <td rowspan="{{$assigned->client->assigned_products->count() ?? 0}}" valign="middle" style="text-align: center; border:1px solid black;" >{{$assigned->client->assigned_products->count() ?? 0}}</td>
                        <td style="border:1px solid black;">{{$product->product->serial_no ?? "--"}}</td>
                        <td style="border:1px solid black;"> </td>
                        <td style="border:1px solid black;"> </td>
                    @else
                        <td style="border:1px solid black;">{{$counter}}</td>
                        <td style="border:1px solid black;"></td>
                        <td style="border:1px solid black;">{{$assigned->client->branch_name}}</td>
                        <td style="border:1px solid black;">{{$product->product->name}}</td>
                        <td style="border:1px solid black;">{{$product->date_of_install ?? "--"}}</td>
                        <td style="border:1px solid black;">{{$product->product->brand->name ?? "--"}}</td>
                        <td style="border:1px solid black;">{{$product->product->model_no ?? "--"}}</td>
                        <td style="border:1px solid black;">{{$product->product->serial_no ?? "--"}}</td>
                        <td style="border:1px solid black;"> </td>
                        <td style="border:1px solid black;"> </td>
                        <td style="border:1px solid black;"> </td>
                    @endif
                    @foreach(range(1, 4) as $index => $number)
                        @php
                            $data_array = [];
                            $data = returnQuarterData($dsr_grouped, $assigned->user->id, $assigned->client->id, (4 - $index), $product->product->id);
                            $data_array = array_merge($data_array, generateQuarterData($data, (4 - $index)));
                            // dd($data_array);
                            // if($product->product->id == 1227){
                            //     dump($product->product->id);
                            //     dump($data);
                            //     dump($data_array);
                            // }
                            $amount = findAmcAmount($assigned->client->amc_active, $product, (4 - $index), $financial_year);

                            echo __("<td style='border:1px solid black;' class='bg-success'>".($amount ?? 0.00)."</td>");
                            foreach ($data_array as $index_name => $value) {
                                echo __("<td style='border:1px solid black;' class='bg-success'>".$value."</td>");
                            }
                        @endphp
                    @endforeach
                    
                    @php                    
                        // if($product->product->id == 1227){
                        //     dd("HALT HERE");
                        // }
                        $counter += 1;
                    @endphp
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>