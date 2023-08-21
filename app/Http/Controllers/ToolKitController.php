<?php

namespace App\Http\Controllers;

use App\Models\ToolKit;
use App\Models\ToolkitRequest;
use Crypt;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Session;
use Validator;

class ToolKitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tool_kits = ToolKit::where('status', 1)->get();
        return view('admin.tool-kit.index', compact('tool_kits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tool-kit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $rules = [

                'name'                  => 'required',
                'quantity_to_be_issued' => 'required',
            ];

            $messages = [
                'name.required'                  => 'Tool kit name is required',
                'quantity_to_be_issued.required' => 'Tool kit quantity required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = $request->all();

            ToolKit::create($data);
        } catch (ValidationException $e) {
            return Redirect::back();
        }

        Session::flash('success', 'Successfully added tool-kit deatils');
        return redirect()->route('view-all-tool-kit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tool_kit_id = Crypt::decrypt($id);
        $tool_kit    = ToolKit::where('id', $tool_kit_id)->where('status', 1)->first();
        return view('admin.tool-kit.show', compact('tool_kit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tool_kit_id = Crypt::decrypt($id);
        $tool_kit    = ToolKit::where('id', $tool_kit_id)->where('status', 1)->first();
        return view('admin.tool-kit.edit', compact('tool_kit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tool_kit_id = Crypt::decrypt($id);
        try
        {
            $rules = [

                'name'                  => 'required',
                'quantity_to_be_issued' => 'required',
            ];

            $messages = [
                'name.required'                  => 'Tool kit name is required',
                'quantity_to_be_issued.required' => 'Tool kit quantity required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Please fix the error and try again!');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $tool_kit                        = ToolKit::where('id', $tool_kit_id)->where('status', 1)->first();
            $tool_kit->name                  = $request->name;
            $tool_kit->quantity_to_be_issued = $request->quantity_to_be_issued;
            $tool_kit->remarks               = $request->remarks;
            $tool_kit->save();

        } catch (ValidationException $e) {
            return Redirect::back();
        }

        Session::flash('success', 'Successfully updated tool-kit deatils');
        return redirect()->route('view-all-tool-kit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tool_kit_id      = Crypt::decrypt($id);
        $tool_kit         = ToolKit::where('id', $tool_kit_id)->where('status', 1)->first();
        $tool_kit->status = 0;
        $tool_kit->save();

        Session::flash('success', 'Successfully deleted tool-kit deatils');
        return redirect()->route('view-all-tool-kit');
    }

    public function export(Request $request)
    {
        $tool_kits = ToolKit::where('status', 1)->orderBy('id', 'desc')->get();

        try {
            Excel::create('ToolKitDetails ' . date('dmyHis'), function ($excel) use ($tool_kits) {
                $excel->sheet('ToolKit-Details ', function ($sheet) use ($tool_kits) {
                    $sheet->setTitle('ToolKit-Details');

                    $sheet->cells('A1:E1', function ($cells) {
                        $cells->setFontWeight('bold');
                    });

                    $arr = [];
                    foreach ($tool_kits->chunk(500) as $res):
                        foreach ($res as $k => $v) {
                            //dd($v);
                            $arr[$k]['Sl No']         = $k + 1;
                            $arr[$k]['Tool kit code'] = $v->tool_kit_code;
                            $arr[$k]['Tool kit name'] = $v->name;
                            $arr[$k]['Quantity']      = $v->quantity_to_be_issued;

                            $arr[$k]['Remarks'] = $v->remarks;

                        }

                    endforeach;
                    $sheet->fromArray($arr, null, 'A1', false, true);

                });
            })->download('xlsx');
        } catch (Exception $e) {
            Session::flash('error', 'Unable to export !');
            return Redirect::back();
        }

        Session::flash('success', 'Successfully exported tool-kit details');
        return Redirect::route('view-all-tool-kit');
    }
    public function allRequestedToolkit(Request $request)
    {
        $paginate = 100;

        $requested_toolkits = ToolkitRequest::query();
        $requested_toolkits = $requested_toolkits->with("requested_by", "issued_by", "item", "client")
            ->orderBy("created_at", "DESC");
        $date_from = "01-01-2019";
        $date_to = date("d-m-Y");
        if($request->get("date_from")){
            $date_from = $request->get("date_from");
        }
        if($request->get("date_to")){
            $date_to = $request->get("date_to");
        }
        // $request->merge([
        //     "date_from" => $date_from,
        //     "date_to"   => $date_to,
        // ]);
        $requested_toolkits = $requested_toolkits->whereRaw("cast(created_at as date) between ? and ?", [
            date("Y-m-d", strtotime($date_from)),
            date("Y-m-d", strtotime($date_to))
        ]);
        $requested_toolkits = $requested_toolkits->where("status", "!=", "deleted");
        if ($request->has('export-data')) {
            return $this->exportExcelToolkits($requested_toolkits->get());
        }
        $requested_toolkits = $requested_toolkits->paginate($paginate);

        return view('admin.tool-kit.requested-index', compact('requested_toolkits'));
    }

    public function updateToolkitIssued(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id    = Crypt::decrypt($encrypted_id);
            $toolkit_request = ToolkitRequest::findOrFail($decrypted_id);
        } catch (Exception $e) {
            \Log::error($e);
            return redirect()
                ->route("toolkit-requested")
                ->with("error", "Whoops! something went wrong. try again later.");
        }
        $rules = [
            "remark"    => "nullable|max:250",
            "issued_at" => "required",
            "status"    => "required|in:issued,deleted",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->route("toolkit-requested")
                ->with("error", "Whoops! looks like you have missed something please try again later.");
        }
        $update_data = [
            "status"         => $request->get("status"),
            "issued_at"      => date("Y-m-d H:i:s", strtotime($request->get('issued_at'))),
            "issued_by_id"   => auth()->id(),
            'issued_remarks' => $request->get("remark"),
        ];
        if ($toolkit_request->update($update_data)) {
            return redirect()
                ->route("toolkit-requested")
                ->with("success", "Request Successfully updated.");
        }
        return redirect()
            ->route("toolkit-requested")
            ->with("error", "Whoops! looks like you have missed something please try again later.");
    }
    private function exportExcelToolkits($requested_toolkits)
    {
        $excel_sheet_title = "Engineer requested reports ".time();
        $excel_sheet_name  = "";
        $export_array      = [];
        foreach ($requested_toolkits as $key => $item) {
            $export_array[] = [
                "Item Name"      => $item->item->name ??  $item->request ?? "NA",
                "Client Name"    => isset($item->client) ? $item->client->name : (isset($item->client_name) ? $item->client_name : "Na"),
                "Branch Name"    => isset($item->client) ? $item->client->branch_name : (isset($item->branch_name) ? $item->branch_name : "Na"),
                "Request for"    => ucwords(str_replace("_", " ", $item->request_for)),
                "Requested By"   => $item->requested_by->full_name(),
                "Requested at"   => date("d-m-Y h:i a", strtotime($item->created_at)),
                "Remarks"        => $item->remarks ?? "NA",
                "Issued at"      => ($item->issued_at ? date("d-m-Y h:i a") : "---"),
                "Issued remarks" => $item->issued_remarks ?? "NA",
                "Status"         => $item->status ?? "NA",
            ];
        }
        Excel::create($excel_sheet_title, function ($excel) use ($export_array, $excel_sheet_name) {
            $excel->sheet($excel_sheet_name, function ($sheet) use ($export_array) {
                $sheet->setTitle('Engineer requested items');
                $sheet->cells('A1:L1', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->freezeFirstRow();
                $sheet->fromArray($export_array, null, 'A1', true, true);
            });
        })->download('xlsx');
    }
}
