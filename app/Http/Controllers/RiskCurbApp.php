<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskCurb;
use App\Models\ApiKeys;
use App\Models\RiskCurbGeneratedContent;
use App\Models\RiskCurbPrompts;
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
    public $organization = "";
    public $organization_type = "";
    public $city = "";
    public $state = "";
    public $country = "";
    public $assets = "";
    public $products = "";
    public $services = "";
    public $structure_type = "";
    public $components = "";
    public $customer_types = "";
    public $stakeholders= "";
    public $workers = "";
    public function __construct()
    {
          $this->tenant_id = "1";
          $this->step = "1";
    }

    public function index()
    {
      $content = "";
      $title = "";

        return view('admin.riskcurb.dashboard', ['content'=>$content ,'title'=> $title]);
        //
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

        $content = trim($result['choices'][0]['text']);
        return $content;
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

   public function strReplaceAssoc(array $replace, $subject) {

        return str_replace(array_keys($replace), array_values($replace), $subject);

     }

    public function adminPromptsApiGenerate(Request $request)
    {
        $section = $request->data['section'];

        $prompt = "";
        $isNew = true;
        $content = "";

        $promptObject = RiskCurbGeneratedContent::where('section',$section)->where('tenant_id', $this->tenant_id)->first();

        $updatepromptState = RiskCurbPrompts::where('section','context')->first();

        if($updatepromptState){
            $prompt = $updatepromptState->prompt;
        }//this is for getting prompt for loaded state section

        $riskcurb_data = RiskCurb::where('tenant_id', $this->tenant_id)->first();//then we get organization information and update function state

        if($riskcurb_data){
            $this->organization = $riskcurb_data->organization;
            $this->organization_type = $riskcurb_data->organization_type;
            $this->city = $riskcurb_data->city;
            $this->state = $riskcurb_data->state;
            $this->country = $riskcurb_data->country;
            $this->assets = $riskcurb_data->assets;
            $this->products = $riskcurb_data->products;
            $this->services = $riskcurb_data->services;
            $this->structure_type = $riskcurb_data->structure_type;
            $this->components = $riskcurb_data->components;
            $this->customer_types = $riskcurb_data->customer_types;
            $this->stakeholders= $riskcurb_data->stakeholders;
            $this->workers = $riskcurb_data->workers;
        }

            $replace = array(

                '$organization' => $this->organization,
                '$organization_type' => $this->organization_type,
                '$city' => $this->city,
                '$state' => $this->state,
                '$country' => $this->country,
                '$assets' => $this->assets,
                '$products' => $this->products,
                '$services' => $this->services,
                '$structure_type' => $this->structure_type,
                '$components' => $this->components,
                '$customer_types' => $this->customer_types,
                '$stakeholders' => $this->stakeholders,
                '$workers' => $this->workers,

                );

            return json_encode($this->str_replace_assoc($replace,$prompt));

        if($promptObject){
            $content = $promptObject->content;
            $isNew = false;

        }else{
            $isNew = true;
            $content = $this->parsePrompt($prompt);


        }

        return json_encode(array('status'=> 200, 'content'=> $content, 'section'=> $section, 'isNew'=> $isNew));
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
    public function adminPrompts(Request $request)
    {
        $section = $request->section;
        $prompt = "";
        $isNew = true;
        $promptObject = RiskCurbPrompts::where('section','context')->first();

        if($promptObject){
            $prompt = $promptObject->prompt;
            $isNew = false;

        }else{
            $prompt = '';
            $isNew = true;

        }
        return view('admin.riskcurb.riskcurbprompts', [
            'prompt'=>$prompt,
            'isNew'=> $isNew,
        ]);
        //
    }
    public function adminpromptsSave(Request $request)
    {
        $section = $request->section;
        $prompt = $request->prompt;
        $isNew = true;
        $promptObject = RiskCurbPrompts::where('section',$section)->first();

        if($promptObject){
            $updatedPrompt = RiskCurbPrompts::where('section',$section)->update(array('prompt'=> $prompt));
            $isNew = false;

        }else{
           $newPrompt = new RiskCurbPrompts();
           $newPrompt->section = $section;
           $newPrompt->prompt = $prompt;
           $newPrompt->save();
           $isNew = true;
        }

        return view('admin.riskcurb.riskcurbprompts', [
            'prompt'=>$prompt,
            'isNew'=> $isNew,
            'section' => $section,
            'success' => __('Prompt Created successfully.')
        ])->with('success', __('Prompt Created successfully.'));
        return redirect()->back()->with('success', __('Prompt Created successfully.'));

    }
    public function adminPromptsApi(Request $request)
    {
        $section = $request->data['section'];

        $prompt = "";
        $isNew = true;

        $promptObject = RiskCurbPrompts::where('section',$section)->first();

        if($promptObject){
            $prompt = $promptObject->prompt;
            $isNew = false;

        }else{
            $prompt = '';
            $isNew = true;

        }

        return json_encode(array('status'=> 200, 'prompt'=> $prompt, 'section'=> $section, 'isNew'=> $isNew));
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
        $content = "";



        $title = $request->title;

        // $client = \OpenAI::client(env('OPENAI_API_KEY'));

        // $result = $this->parsePrompt($title);

        // $content = trim($result['choices'][0]['text']);

        return view('admin.riskcurb.dashboard', ["content"=>$content ,"title"=> $title, "step"=>$step]);

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
