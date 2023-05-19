<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskCurb;
use App\Models\ApiKeys;


class RiskCurbApp extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.riskcurb.dashboard');
        //
    }
    public function apiKeys()
    {
        $openAi = ApiKeys::where('name','openAi')->first();

        return view('admin.riskcurb.apiKeys', [
            'openAi'=>$openAi
        ]);
        //
    }
    public function apiKeysSave(Request $request)
    {
        $openAi = ApiKeys::where('name','openAi')->get();

        if($openAi->count() > 0){
          $updateKey =  ApiKeys::where('name','openAi')->update(array('apikey'=>$request->apikey));
          $openAi = ApiKeys::where('name','openAi')->first();

        }else{
         $newKey = new ApiKeys();
         $newKey->name = 'openAi';
         $newKey->apikey = $request->apikey;
         $newKey->save();
         $openAi = ApiKeys::where('name','openAi')->first();
        }

        return view('admin.riskcurb.apiKeys', [
            'openAi'=>$openAi
        ]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
}
