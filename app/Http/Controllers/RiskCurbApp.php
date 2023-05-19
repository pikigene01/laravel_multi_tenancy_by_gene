<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskCurb;
use App\Models\ApiKeys;
use OpenAI\Client;

class RiskCurbApp extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $content = "";
      $title = "";

        return view('admin.riskcurb.dashboard', ["content"=>$content ,"title"=> $title]);
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
        if ($request->title == null) {
            return;
        }

        $openAi = ApiKeys::where('name','openAi')->get();

        if($openAi->count() > 0){
          $openAi = ApiKeys::where('name','openAi')->first();
          $openAi = $openAi->apikey;
        }else{
         $openAi = "";
        }

        $title = $request->title;

        // $client = \OpenAI::client(env('OPENAI_API_KEY'));
        $client = \OpenAi::client($openAi);

        $result = $client->completions()->create([
            "model" => "text-davinci-003",
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 600,
            'prompt' => sprintf('Write article about: %s', $title),
        ]);

        $content = trim($result['choices'][0]['text']);

        return view('admin.riskcurb.dashboard', ["content"=>$content ,"title"=> $title]);

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
