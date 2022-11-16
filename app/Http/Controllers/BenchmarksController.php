<?php namespace App\Http\Controllers;

use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Benchmarks;
use App\Clients;
use Route;
use App\Http\Requests\BenchmarksRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\ClientMenu;
use Redirect;

class BenchmarksController extends Controller {

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('benchmark', $clientSelectedMenus))
              Redirect::to('access-restricted')->send();
        }    
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$benchmarks = Benchmarks::all();

		if(Session::get('hostname') == 'crm')
			return view('benchmarks.index', compact('benchmarks'));
		else
			return view('Result.benchmarks.index', compact('benchmarks'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		$benchmarks = Benchmarks::findOrFail($id);

		if(Session::get('hostname') == 'crm')
			return view('benchmarks.show', compact('benchmarks'));
		else
			return view('Result.benchmarks.show', compact('benchmarks'));
		
	}

    /**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		if(Session::get('hostname') == 'crm')
			return view('benchmarks.create');
		else
			return view('Result.benchmarks.create');
		
	}

    /**
	 * Store a newly created resource in storage.
	 *
	 * @param  BenchmarksRequest $request
	 * @return Response
	 */
	public function store(Request $request){ 
		$isError = false;
		$postData = $request->all();
		$editId = $postData['banchmarkId'];

		if(Session::get('hostname') == 'crm'){
	        if($editId == ""){
	        	if(!hasPermission('create-benchmark'))
	                 $isError = true;
	        }
	        else{
	        	if(!hasPermission('edit-benchmark'))
	                $isError = true;
	        }
	    }
       
		$lastInsertId = '';
		if(!$isError){
			if($postData["form_no"] == 1) {
				if($postData["bm_time_opt"] == 'Automatic Time Entry') {
					$data['nps_automatic_time'] = 1;
					$data["nps_manual_time"] = 0;
					$data['nps_day']=Carbon::now();
					$data['nps_time_hour'] = date("H");
					$data['nps_time_min'] = date("i");
				} else {
					$data["nps_manual_time"] = 1;
					$data['nps_automatic_time'] = 0;
					$bdate = date('Y-m-d',strtotime($postData["bm_time_day"]));
					$data['nps_day'] = $bdate." ".date("h:i:s");
					$data['nps_time_hour'] = $postData["bm_time_hour"]; 
					$data['nps_time_min'] = $postData["bm_time_min"]; 
				}
				$data['client_id'] = $postData['client_id'];
				if($editId != ""){
					$lastInsertId = $editId;
					$data['updated_at']=Carbon::now();
					$benchmark = Benchmarks::updateBenchmarks($data,$lastInsertId);
				}
				else{
					
					$benchmark = Benchmarks::create($data);
					$lastInsertId = $benchmark->id;
				}	

			} else if($postData["form_no"] == 2){
				$data['benchmarkTemperature'] = $postData["bm_temp"];
				$data['stress'] = $postData["stress"];
				$data['sleep'] = $postData["sleep"];
				$data['hydration'] = $postData["hydration"];
				$data['humidity'] = $postData["humidity"];
				$data['nutrition'] = $postData["nutrition"];
				$lastInsertId = $postData["last_insert_id"];
				$benchmark = Benchmarks::updateBenchmarks($data,$lastInsertId);
			} else if($postData["form_no"] == 3){
				$data['waist'] = $postData["bm_waist" ];
				$data['hips'] = $postData["bm_hips"];
				$data['height'] = $postData["bm_height"];
				$data['weight'] = $postData["bm_weight"];
				$lastInsertId = $postData["last_insert_id"];
				$benchmark = Benchmarks::updateBenchmarks($data,$lastInsertId);
			} 
		}	
		if(!is_null($benchmark)){
			$message = array("status"=>"success","benchmark"=>$lastInsertId);
		} else {
			$message = array("status"=>"false","benchmark"=>null);
		}
		echo json_encode($message);
	}

	public function lastStore(Request $request){

		$formData = array();
		parse_str($_GET['data'], $formData);
		$lastId = $formData['last_insert_id'];
		$data['pressups'] = $formData["bm_pressups"];
		$data['plank'] = $formData["bm_plank"];
		$data['timetrial3k'] = $formData["bm_timetrial3k"];
		$data['cardiobpm1'] = $formData["bm_bpm1"];
		$data['cardiobpm2'] = $formData["bm_bpm2"];
		$data['cardiobpm3'] = $formData["bm_bpm3"];
		$data['cardiobpm4'] = $formData["bm_bpm4"];
		$data['cardiobpm5'] = $formData["bm_bpm5"];
		$data['cardiobpm6'] = $formData["bm_bpm6"];

		 
		$message = array();
		$benchmark = Benchmarks::updateBenchmarks($data,$lastId);
		if($benchmark == 0 || $benchmark != 0){
			$message = array("status"=>"success");
		} 
		echo json_encode($message);
		
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$benchmarks = Benchmarks::findOrFail($id);

        return view('benchmarks.edit', compact('benchmarks'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param  BenchmarksRequest $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
        $benchmarks = Benchmarks::findOrFail($id);
        $benchmarks->update($request->all());

        return redirect('/benchmarks');
	}

	/**
	 * get the all details from menchmark table .
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDetails($id)
	{
		if(Session::get('hostname') == 'crm' && !hasPermission('view-benchmark'))
               abort(404);

        $allbenchmarks = Benchmarks::find($id);
        $allbenchmarks->created_at = setLocalToBusinessTimeZone($allbenchmarks->created_at);
        return json_encode($allbenchmarks);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
	{
	   if(Session::get('hostname') == 'crm' && !hasPermission('delete-benchmark'))
            abort(404);
        
       $root = route('clients.show',$request->clientId).'#benchmarks';
		$benchmarks = Benchmarks::findOrFail($id);
		$benchmarks->delete();
        //clients.show 
		return redirect($root);
	}


	/** 
	 * Get client details for previous data
	 * @param
	 * @return
	**/
	public function getClient() {
		$clientInfo = array();
		$clientId = $_POST['id'];
		$client = \App\Clients::find($clientId);
		$parq = $client->parq;
		if($parq){
			$clientInfo['status'] = "success";
			$clientInfo['height'] = $parq->height;
			$clientInfo['weight'] = $parq->weight;
		}
		return json_encode($clientInfo);
	}


	/**
     * get benchmark details
     * @param void
     * @return benchmark view
    **/
    public function benchmarkDetails() {
		$selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('benchmark', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
        $businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $clients = Clients::with('benchmarks')->find($clientId);
        $benchmarks = $clients->benchmarks;
        return view('Result.benchmark.view.benchmark', compact('clients', 'benchmarks'));
    }

}
