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
    public $tenant_id;
    public $step;
    public function __construct()
    {
          $this->tenant_id = "1";
          $this->step = "1";
    }

    public function index()
    {
      $content = "";
      $title = "";

        return view('admin.riskcurb.dashboard', ["content"=>$content ,"title"=> $title]);
        //
    }
    public function indexFramework()
    {
      $content = "No created framework for now!!!";
      $title = "";
      $data = RiskCurb::where('tenant_id', $this->tenant_id)->first();
      if( $data){
          $this->step =  $data->steps;
      }else{
          $this->step = 1;
      }

        return view('admin.riskcurb.framework', ["content"=>$content ,"title"=> $title, "step"=>$this->step,"data"=>$data]);
        //
    }
    public function indexReports()
    {
      $content = "No Reports for now!!!";
      $title = "";

        return view('admin.riskcurb.reports', ["content"=>$content ,"title"=> $title]);
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
        $step = "1";



        $title = $request->title;

        // $client = \OpenAI::client(env('OPENAI_API_KEY'));

        $result = $this->parsePrompt($title);

        $content = trim($result['choices'][0]['text']);

        return view('admin.riskcurb.dashboard', ["content"=>$content ,"title"=> $title, "step"=>$step]);

    }

    public function parsePrompt($prompt = ""){
        $openAi = ApiKeys::where('name','openAi')->get();

        if($openAi->count() > 0){
          $openAi = ApiKeys::where('name','openAi')->first();
          $openAi = $openAi->apikey;
        }else{
         $openAi = "";
        }

        $client = \OpenAi::client($openAi);

        $result = $client->completions()->create([
            "model" => "text-davinci-003",
            "temperature" => 0.7,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            'max_tokens' => 600,
            'prompt' => sprintf('Write article about: %s', $prompt),
        ]);

        return $result;
    }
    public function createFramework(Request $request)
    {
        // dd($request);
        $content = "";
        $title = "";
        $riskcurb_step = RiskCurb::where('tenant_id', $this->tenant_id)->first();
        if( $riskcurb_step){
            $this->step =  $riskcurb_step->steps += 1;
        }else{
            $this->step = 1;
        }
        $dataValues = array(
          "organization"=>$request->organization,
          "organization_type"=>$request->organization_type,
          "location"=>$request->location,
          "city"=>$request->city,
          "state"=>$request->state,
          "country"=>$request->country,
          "assets"=>$request->assets,
          "products"=>$request->products,
          "services"=>$request->services,
          "structure_type"=>$request->structure_type,
          "components"=>$request->components,
          "customer_types"=>$request->customer_types,
          "stakeholders"=>$request->stakeholders,
          "workers"=>$request->workers,
          "steps"=>$this->step,
        );

        $riskcurb_data = RiskCurb::where('tenant_id', $this->tenant_id)->get();
        if($riskcurb_data->count() > 0){
            $update_request = RiskCurb::where('tenant_id', $this->tenant_id)->
            update($dataValues);

        }else{
            $riskcurb_model = new RiskCurb();
            $riskcurb_model->tenant_id = $this->tenant_id;
            $riskcurb_model->organization = $request->organization;
            $riskcurb_model->save();
        }

      if($this->step >= "12"){
        $result = $this->parsePrompt("Risk likely to face this organisation with stated information");

        $content = $result;
      }

        return view('admin.riskcurb.framework', ["content"=>$content ,"title"=> $title, "step"=>$this->step,"data"=>$riskcurb_step]);

    }

    public function apiKeysRemove()
    {
        $content = "";
        $title = "";
        $riskcurb_step = RiskCurb::where('tenant_id', $this->tenant_id)->first();
        if( $riskcurb_step){
            $this->step = 1;
            RiskCurb::where('tenant_id', $this->tenant_id)->update(array('steps'=>$this->step));
        }else{

        }
        return view('admin.riskcurb.framework', [
            'step'=>'1',"data"=>$riskcurb_step,
            "content"=>$content ,"title"=> $title
        ]);
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
