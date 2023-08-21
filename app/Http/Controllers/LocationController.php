<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth,Session,DB,Crypt,Validator,Excel;

use App\Models\UserTrackLocation ,App\User;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status',1)->get();
        return view('admin.track-location.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function trackLocationDetails(Request $request)
    {
       $engineer_id = Crypt::decrypt($request->user_id);

       //dd($engineer_id);
       $track_date = date('Y-m-d',strtotime($request->track_date)); 
       // dd($track_date);
    //    $today = date('Y-m-d', strtotime(date("Y-m-d")." +1 days"));
       // dd($track_date);

    //    $range=[$track_date,$today];

       $track_details = UserTrackLocation::with('user')
        ->where('status',1)
        ->where('engineer_id',$engineer_id)
        ->whereRaw('cast(track_date as date) = ?', [$track_date])
        ->orderBy('id','desc')->get();


       $engineer_name = User::where('id',$engineer_id)->where('status',1)->first();

       // dd($track_details);
       return view('admin.track-location.location-tracking-map',compact('track_details','engineer_name','track_date'));
    }

    public function engineerLocations(Request $request) {
        // $where = [];
        $engineer_id = $request->user_id;
        $track_date = date('Y-m-d',strtotime($request->track_date));
        $today = date("Y-m-d");
       // return($track_date);

        // $range=[$track_date, $today];

        return UserTrackLocation::where('status',1)
        ->where('engineer_id',$engineer_id)
        ->whereRaw('cast(track_date as date) = ?', [$track_date])
        ->orderBy('id', 'DESC')
        ->with('user')->get();

    }
}
