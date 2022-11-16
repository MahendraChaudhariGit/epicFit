<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use Auth;
use App\ClientMenu;
use App\FitnessMap;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Redirect;

use App\{Challenge,ChallengeType,ActivityType,ChallengeFriend};

class MapController extends Controller{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('fitness_mapper', $clientSelectedMenus))
              Redirect::to('access-restricted')->send();
        }    
    }
 	public function show(Request $request, $new_challenge=''){
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('fitness_mapper', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
        $search = $request->search;
        $page = $request->page;
        $data = FitnessMap::where('client_id', Auth::user()->account_id);
        if(!empty($request->search) && $request->search != 'null'){
            $data = $data->where(function($query) use($search){
                $query->orWhere('name', 'like', "%$search%")
                ->orWhere('city','like',"%$search%")
                ->orWhere('km','like',"%$search%")
                ->orWhere('created_at','like',"%$search%");
            });
        }
        $data = $data->orderBy('id','desc')->get();
        /* 25-05-2021 */
          $client_ids = ChallengeFriend::select('challenge_id','client_id')->where('client_id', Auth::user()->account_id)
                        ->orderBy('id','desc')
                        ->get();
          $client_array = [];
          foreach($client_ids as $key => $id){
              $client_array[] = $id['challenge_id'];
           }
          $challenge = Challenge::with(['challenge_type','activity_type','fitness_mapper_route','challenge_friend.client'])
                        ->where('client_id', Auth::user()->account_id)
                        ->orwhereIn('id',$client_array)
                        ->orderBy('id','desc')
                        ->get();
        //   $challenge_invitation = ChallengeFriend::with(['challenge.fitness_mapper_route','challenge.challenge_type','challenge.activity_type','client'])
        //                 ->where('client_id', Auth::user()->account_id)
        //                 ->orderBy('id','desc')
        //                 ->get();

        /* 25-05-2021 */  
        return view('Result.fitness-map.show',compact('data','search','page','challenge','new_challenge'));
	}
    public function save(Request $request){
        $fitness_map = FitnessMap::where('client_id', Auth::user()->account_id)->where('id',$request->map_id)->first();
        $map = new FitnessMap();
        $msg = [];
        $data['client_id'] = Auth::user()->account_id;
        $data['point_count'] = $request->pointsCount;
        $data['email'] = $request->email;
        $data['city'] = $request->CityName;
        $data['name'] = $request->RouteName;
        $data['description'] = $request->RouteDesc;
        $data['km'] = $request->Quantity;
        $data['is_avail_to_public'] = $request->IsAvailableToPublic;
        $data['is_avail_to_friends'] = $request->IsAvailableToFriends;
        $data['city_id'] = $request->CityID;
        $data['keywords'] = $request->KeyWords;
        $data['location'] = $request->Location;
        $data['level'] = $request->Levels;
        $data['polyline'] = $request->Polyline;
        $data['scenic_rating'] = $request->ScenicRating;
        $data['times_used'] = $request->TimesUsed;
        $data['activity_idf_as_test_bike'] = $request->ActivityIDFastestBike;
        $data['activity_idf_as_test_foot'] = $request->ActivityIDFastestFoot;
        $data['altitude_gain_meter'] = $request->AltitudeGainMeters;
        $data['max_altitude_meters'] = $request->MaxAltitudeMeters;
        $data['exercise'] = $request->Exercise;
        $data['workout'] = $request->Workout;
        $data['duration'] = $request->Duration;
        $data['actual_duration'] = $request->ActualDuration;
        $data['created_at'] = now();
        if(!isset($fitness_map)){
            $save = $map->create($data);
            // dd($save);
            $msg['success'] = 'Data Saved Successfully.';
            if(!empty($request->new_challenge)){
                $msg['challenge'] = $request->new_challenge;
                $msg['fit_map_id'] = $save->id;
            }
            
        }else{
            $fitness_map->update($data);
            $msg['success'] = 'Data Updated Successfully.';
        }
        // if($data){
        // $map->create($data);
        // $msg['success'] = 'Data Saved Successfully.';
        // }else{
        //     $msg['error'] = 'Error.';
        // }
        return $msg;

   }
   public function delete($id){
        $data = FitnessMap::where('id',$id)->first();
        if( $data){
            $data->delete();
            // return redirect()->back()->with('message', 'success|Data has been deleted successfully.');
            $response = [
                'status' => 'success',
                'message' => 'success|Data has been deleted successfully.',
            ];
            return response()->json($response);
        }
   
    }
    public function edit(Request $request){
        $data = FitnessMap::where('id',$request->id)->first();
        if( $data){
            return $data;
        }
    }
    public function editRoute(Request $request){
        $data = FitnessMap::where('id',$request->id)->first();
        if( $data){
            return view('Result.fitness-map.edit',compact('data'));
        }
    }
    public function copyRoute(Request $request){
        $data = FitnessMap::where('id',$request->id)->first();
        if( $data){
            return view('Result.fitness-map.copy',compact('data'));
        }
    }
    public function detail(Request $request, $id){
        $data = FitnessMap::where('id',$request->id)->first();
        if(isset($data)){

            $encodedData = $this->decode($data->polyline);
            // dd($encodedData);
            $pathData =[];
            $levelData =[];
            $elevationData = [];
            $elevationLevelData = [];
            foreach( $encodedData as $value)
            {
                $url= 'https://maps.googleapis.com/maps/api/elevation/json?locations='.$value[0].','.$value[1].'&key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg';
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $output=curl_exec($ch);
                $pathData[] = json_decode($output,1);
                curl_close($ch);
            }
            foreach($pathData as $key => $value){
                $elevationData[$key] = round($value['results'][0]['elevation']);
            }
            foreach( $encodedData as $key=>$value)
            {
                if($key == 0){
                    $origin_lat = $value[0];
                    $origin_lng = $value[1];
                }
                $url= "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin_lat.",".$origin_lng."&destinations=".$value[0].",".$value[1]."&mode=driving&language=pl-PL&key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg";
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $output=curl_exec($ch);
                $levelData[] = json_decode($output,1);
                curl_close($ch);
            }
            foreach($levelData as $key => $value){
                $elevationLevelData[$key] = number_format((float)($value['rows'][0]['elements'][0]['distance']['value']*0.000621371), 2, '.', '');
            }
            $client_details = Client::select('id','firstname','lastname','profilepic')->find(Auth::user()->account_id);
            $elevationData = json_encode($elevationData); 
            $elevationLevelData = json_encode($elevationLevelData); 

            $challenge_data = Challenge::with(['challenge_friend.client'=>function($query){
                               $query->select('id','firstname','lastname','profilepic');
                               },
                              'challenge_friend'=>function($q){
                                 $q->where('status','Accepted')
                                 ->orderBy('complete_time', 'ASC');
                              }])
                            ->where('fitness_mapper_route_id',$request->id)
                            ->where('client_id',Auth::user()->account_id)
                            ->first();
            //  dd($challenge_data);
            return view('Result.fitness-map.detail',compact('data','client_details','elevationData','elevationLevelData','challenge_data'));
            
        }else{
            return redirect('epic/train-gain/fitness-mapper');
        }
    }

    public function search(){
        return view('Result.fitness-map.search');
    }
    public function route(Request $request){
        $search_data = $request->all();
        $query = FitnessMap::select('*')->where('client_id', Auth::user()->account_id);
        if($request->routeName != ''){
            $query->where('name','LIKE',"%$request->routeName%");
        }
        if(isset($request->activityType) && $request->activityType != ''){
            $query->where('workout',$request->activityType);
        }
        if(isset($request->distanceType) && $request->distanceType != ''){
            if($request->distanceType == 'atleast'){
                $km=  $request->unit == 'km'?$request->distanceMinimum:$request->distanceMinimum * 1.609;
                $query->where('km','>=',$km);
            }else if($request->distanceType == 'between'){
                $minDistance=  $request->unit == 'km'?$request->distanceMinimum:$request->distanceMinimum * 1.609;
                $maxDistance =$request->unit == 'km'?$request->distanceMaximum:$request->distanceMaximum * 1.609;
                $query->whereBetween('km',[$minDistance,$maxDistance]);
            }
            
        }
       $routesData = $query->get();
       $filterData = collect();
       $latroot = [];
       $lngroot = [];
       $i= 0;

        foreach($routesData as $route){
            $encodedData = $this->decode($route->polyline);
            foreach($encodedData as $value){
                $distance = $this->getDistance($request->latitude, $request->longitude, $value[0], $value[1]);
                if($distance < ($request->radius/1000)){
                    $filterData->push($route);
                    $latroot[] = $value[0];
                    $lngroot[] = $value[1];
                    break;
                }
            }
        }
        $center = [];
        if($request->has('latitude') && $request->has('longitude')){
           $center['lat'] =  $request->latitude;
           $center['long'] = $request->longitude;
        }
        return view('Result.fitness-map.search',compact('filterData','center','latroot','lngroot','search_data'));
  }

  public function  getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
    $earth_radius = 6371;
  
    $dLat = deg2rad($latitude2 - $latitude1);  
    $dLon = deg2rad($longitude2 - $longitude1);  
  
    $a = (sin($dLat/2) *sin($dLat/2)) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2));  
    $c = 2 * asin(sqrt($a));  
    $d = $earth_radius * $c;  
  
    return $d;  
  }
  public function decode($string){
    // $string = "udgiEctkwIldeRe}|x@cfmXq|flA`nrvApihC";
    # Step 11) unpack the string as unsigned char 'C'
    $byte_array = array_merge(unpack('C*', $string));
    $results = array();
    
    $index = 0; # tracks which char in $byte_array
    do {
      $shift = 0;
      $result = 0;
      do {
        $char = $byte_array[$index] - 63; # Step 10
        # Steps 9-5
        # get the least significat 5 bits from the byte
        # and bitwise-or it into the result
        $result |= ($char & 0x1F) << (5 * $shift);
        $shift++; $index++;
      } while ($char >= 0x20); # Step 8 most significant bit in each six bit chunk
        # is set to 1 if there is a chunk after it and zero if it's the last one
        # so if char is less than 0x20 (0b100000), then it is the last chunk in that num
    
      # Step 3-5) sign will be stored in least significant bit, if it's one, then 
      # the original value was negated per step 5, so negate again
      if ($result & 1)
        $result = ~$result;
      # Step 4-1) shift off the sign bit by right-shifting and multiply by 1E-5
      $result = ($result >> 1) * 0.00001;
      $results[] = $result;
    } while ($index < count($byte_array));
    
    # to save space, lat/lons are deltas from the one that preceded them, so we need to 
    # adjust all the lat/lon pairs after the first pair
    for ($i = 2; $i < count($results); $i++) {
      $results[$i] += $results[$i - 2];
    }
    
    # chunk the array into pairs of lat/lon values
    $encodeValue =array_chunk($results, 2);
    return  $encodeValue;
  }
}
?>