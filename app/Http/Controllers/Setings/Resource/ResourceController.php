<?php
namespace App\Http\Controllers\Setings\Resource;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Resource;
use App\Location;
use Session;
use App\ResourceItems;
use App\Http\Traits\HelperTrait;
use Input;
use DB;
use App\Http\Traits\ResourceTrait;

class ResourceController extends Controller{
    use HelperTrait, ResourceTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $cookieSlug = 'resource';
    public function index(Request $request){

        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'list-resource')){
               abort(404);
          }

        $allresource = array();
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);

        if($search)
            $allresource = Resource::with('items.location')->where('res_business_id',Session::get('businessId'))->where('res_name', 'like', "%$search%")->paginate($length);
        else
            $allresource = Resource::with('items.location')->where('res_business_id',Session::get('businessId'))->paginate($length);
       
        return view('Settings.resource.index',compact('allresource'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(){
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'add-resource')){
                abort(404);
        }

        $location = Location::where('business_id',Session::get('businessId'))->select('id','location_training_area')->get();
        $loc = [''=>'-- Select --'];
        foreach($location as $locationn)
            $loc[$locationn->id] = ucfirst($locationn->location_training_area);
        asort($loc);
        return view('Settings.resource.create',compact('loc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request){
                $isError = false;
                if(!Session::has('businessId') || !isUserEligible(['Admin'], 'add-resource')){
                        if($request->ajax())
                            $isError = true;
                        else
                            abort(404);
                }

                if($request->ajax())
                    $msg = [];
                if(!$isError){

                   $resource=new Resource;
                   $resource->res_name=$request->resName;
                   $resource->res_business_id=Session::get('businessId');
                   if($resource->save())
                   { 
                    Session::put('ifBussHasResources', true);
                    $res_id=$resource->id;
                    $formData =$request->all();
                    ksort($formData);
                    $newresItem = $newresLoc = [];
                    foreach($formData as $key => $value){
                        if(strpos($key, 'newResItem') !== false)
                            $newresItem[] = $value;
                        else if(strpos($key, 'newResLoc') !== false)
                            $newresLoc[] = $value;
                    }

                    if(count($newresItem) && count($newresLoc)){
                        $newresArray =[];
                        for($i=0;$i<count($newresItem);$i++){
                            $timestamp = createTimestamp();
                            $newresArray[] = ['ri_business_id'=>Session::get('businessId'),'ri_name'=>$newresItem[$i],'ri_location'=>$newresLoc[$i],'ri_id'=>$res_id,'created_at'=>$timestamp,'updated_at'=>$timestamp];  
                        }
                        if(count($newresArray))
                            ResourceItems::insert($newresArray);
                    }
                    
                  } 
            
                if($request->ajax()){
                    $msg['status'] = 'added';
                    $msg['message'] = displayAlert('success|Data has been saved successfully.');
                }
                else
                    Session::flash('flash_message', 'Data has been saved successfully.');
        }

        if($request->ajax())
            return json_encode($msg);
        else{
            if($isError)
                abort(404);
            else
                return redirect('resources.list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id){
        $resource = Resource::findOrFail($id);

        return view('admin.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id){
         if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-resource')){
                abort(404);
          }
          $resources = Resource::with('items', 'resourceServices', 'resourceClases')->where('res_business_id',Session::get('businessId'))->find($id);

          $location = Location::where('business_id',Session::get('businessId'))->select('id','location_training_area')->get();
          $loc = [''=>'-- Select --'];
            foreach($location as $locationn)
                $loc[$locationn->id] = ucfirst($locationn->location_training_area);
            asort($loc);

            $services = Resource::services($resources->resourceServices);
            $clases = Resource::clases($resources->resourceClases);
         
        return view('Settings.resource.edit', compact('resources', 'loc', 'services', 'clases'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request){
       $isError = false;
       if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-resource')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
       
        if($request->ajax()){
            $msg = [];
            $resource = Resource::find($id);
            if(!$resource){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('invalidRecord' => 'This resource doesn\'t exist');
                $isError = true;
            }
        }
            $resource->res_name = $request->resName;
            $resource->res_business_id = Session::get('businessId');
            
            if($resource->save()){
                $formData =$request->all();
                    ksort($formData);
                    $resItem = $resLoc = $itemId = $newresItem = $newresLoc = [];
                    foreach($formData as $key => $value){
                        if(strpos($key, 'resItem') !== false)
                            $resItem[] = $value;
                        else if(strpos($key, 'resLoc') !== false)
                            $resLoc[] = $value;
                        else if(strpos($key, 'itemId') !== false)
                            $itemId[] = $value;
                        else if(strpos($key, 'newResItem') !== false)
                            $newresItem[] = $value;
                        else if(strpos($key, 'newResLoc') !== false)
                            $newresLoc[] = $value;
                    }

                    if(count($resItem) && count($resLoc) && count($itemId)){
                        for($i=0;$i<count($resItem);$i++){
                        ResourceItems::where('id',$itemId[$i])->where('ri_business_id',Session::get('businessId'))->update(['ri_name'=>$resItem[$i] ,'ri_location'=>$resLoc[$i]]);
                        }
                    }
                    
                    //ResourceItems::whereNotIn('id',$itemId )->where('ri_id',$id)->delete();
                    ResourceItems::whereNotIn('id',$itemId )->where('ri_id',$id)->forceDelete();

                    if(count($newresItem) && count($newresLoc)){
                        $newresArray =[];
                        for($i=0;$i<count($newresItem);$i++){
                            $timestamp = createTimestamp();
                            $newresArray[] = ['ri_business_id'=>Session::get('businessId'),'ri_name'=>$newresItem[$i],'ri_location'=>$newresLoc[$i],'ri_id'=>$id,'created_at'=>$timestamp,'updated_at'=>$timestamp];  
                        }
                        if(count($newresArray))
                            ResourceItems::insert($newresArray);
                    }

                    $services = Resource::services($resource->resourceServices);
                    if(count($services)){
                        //Getting services id and its location id
                        $servicesWithLoc = $services->pluck('location', 'id')->toArray();

                        $this->manageServiceAndClass($resource, $servicesWithLoc, 'service');
                    }

                    $clases = Resource::clases($resource->resourceClases);
                    if(count($clases)){
                        //Getting clases id and its location id
                        $clasesWithLoc = $clases->pluck('cl_location_id', 'cl_id')->toArray();

                        $this->manageServiceAndClass($resource, $clasesWithLoc, 'clas');
                    }

                if($request->ajax()){
                    $msg['status'] = 'updated';
                    $msg['message'] = displayAlert('success|Data has been updated successfully.');
                }
                else
                    Session::flash('flash_message', 'Data has been updated successfully.');
            }

            if($request->ajax())
                return json_encode($msg);
            else{
               if($isError)
                   abort(404);
             else
                return redirect('admin/resources');
        
        }
    }
    
    /**
     * Update item quantity of service and class
     *
     * @param collection $resource
     *
     * @return Response
     */ 
    protected function manageServiceAndClass($resource, $entitiesWithLoc, $type){
        /*Get linked service id [serv1, serv2, serv3, serv4]
        Get location of those services ['serv1' => 'loc5', 'serv2' => 'loc6', 'serv3' => 'loc5', 'serv4' => 'loc7']
        Get item count from 'resources_items' based on those distinct location ['loc5' => 'itemcount3', 'loc6' => 'itemcount4', 'loc7' => 'itemcount0']
        Get item count from 'service_resources' based on those services['serv1' => 'itemcount4', 'serv2' => 'itemcount3', 'serv3' => 'itemcount3', 'serv4' => 'itemcount10']
        Compare service's item count with its corresponsing location's item count. 
        if(location item count && location item count < service item count)
            service item count = location item count
        else if(!location item count)
            delete service item record*/

        //Getting location id out of services/clases
        $locs = array_unique(array_values($entitiesWithLoc));

        //Getting items count on those particular locations from updated 'resources_items'
        $locItems = $resource->items()->whereIn('ri_location', $locs)->selectRaw('ri_location, count(ri_location) as recCount')->groupBy('ri_location')->get();
        if($locItems->count())
            $locItemsCount = $locItems->pluck('recCount', 'ri_location')->toArray();
        else
            $locItemsCount = [];

        if($type == 'service')
            $linkedEntities = $resource->resourceServices;
        else if($type == 'clas')
            $linkedEntities = $resource->resourceClases;
        //Getting items count on services/clases from 'service_resources'
        $entityItemsCount = $linkedEntities->pluck('sr_item_quantity', 'sr_entity_id')->toArray();

        if(!count($locItemsCount)){
            //If locations has been removed from the items
            if($type == 'service')
                $resource->resourceServices()->delete();
            else if($type == 'clas')
                $resource->resourceClases()->delete();
        }
        else{
            $linkedEntityDel = [];
            $linkedEntityUpd = '';
            foreach($entitiesWithLoc as $entity => $loc){
                if(!array_key_exists($loc, $locItemsCount)) //If service's/clas' location has been removed from the items
                    $linkedEntityDel[] = $entity;
                else if($locItemsCount[$loc] < $entityItemsCount[$entity]){ 
                    //If item count of location has been updated making it less than the item count of service/clas
                    $pk = $linkedEntities->where('sr_entity_id', $entity)->first()->sr_id;
                    $linkedEntityUpd .= "($pk, $locItemsCount[$loc]), ";
                }
            }
            if(count($linkedEntityDel)){
                if($type == 'service')
                    $resource->resourceServices()->whereIn('sr_entity_id', $linkedEntityDel)->delete();
                else if($type == 'clas')
                    $resource->resourceClases()->whereIn('sr_entity_id', $linkedEntityDel)->delete();
            }

            if($linkedEntityUpd){
                $linkedEntityUpd = rtrim($linkedEntityUpd,", ");
                DB::insert("INSERT into `service_resources` (sr_id, sr_item_quantity) VALUES $linkedEntityUpd ON DUPLICATE KEY UPDATE sr_item_quantity = VALUES(sr_item_quantity)");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id){
        if(!isUserEligible(['Admin'], 'delete-resource'))
            abort(404);
        $resource = Resource::find($id);
        $resource->delete();
        return redirect()->back()->with('message', 'success|Resource has been deleted successfully.');
    }

    public function getLocResources($locId){
      return $this->resourceData($locId);
    }
}
