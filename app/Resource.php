<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;
//use App\ServiceResources;
use App\Service;
use App\Clas;
/*use App\StaffEventClass;
use Carbon\Carbon;
use App\StaffEventSingleService;
use App\StaffEventResource;*/

use Illuminate\Database\Eloquent\Model;

class Resource extends Model{
    use SoftDeletes;
    protected $table = 'resources';
    protected $primaryKey = 'id';
    //protected $fillable = ['resName', 'resItem', 'resItemLoc'];
    protected $fillable = ['resName'];
  
    /*
    **start: ACCESSOR
    */

    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */

    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
        public function items(){
            return $this->hasMany('App\ResourceItems','ri_id');
        } 

        public function itemsWithTrashed(){
            return $this->items()->withTrashed();
        } 

        public function resorcesable(){
            return $this->hasMany('App\ServiceResources','sr_res_id');
        } 

        public function resourceServices(){
            return $this->resorcesable()->where('sr_entity_type',"App\Service");
        }

        public function resourceClases(){
            return $this->resorcesable()->where('sr_entity_type',"App\Clas");
        }
    /*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
        static function services($linkedServices){
            if($linkedServices->count()){
                $linkedServices = $linkedServices->pluck('sr_entity_id')->toArray();
                return Service::find($linkedServices);
            }
            return [];
        }

        static function clases($linkedClases){
            if($linkedClases->count()){
                $linkedClases = $linkedClases->pluck('sr_entity_id')->toArray();
                return Clas::find($linkedClases);
            }
            return [];
        }

        static function getServiceQuantity($resource, $entityId){
            return $resource->resourceServices->where('sr_entity_id', $entityId)->first()->sr_item_quantity;
        }

        static function getClassQuantity($resource, $entityId){
            return $resource->resourceClases->where('sr_entity_id', $entityId)->first()->sr_item_quantity;
        }
    /*
    **end: FUNCTIONS
    */


    /*
    **start: EVENTS
    */
         protected static function boot(){
            parent::boot();
            static::deleting(function($res){
                //DB::table('resources_items')->where('ri_id', $res->id)->update(array('deleted_at' => createTimestamp()));
                $res->items()->delete();

                //ServiceResources::where('sr_res_id',$res->id)->where('sr_business_id',Session::get('businessId'))->delete();
                $res->resorcesable()->delete();

                /*$now = new Carbon();
                $futureClassbookings = StaffEventClass::OfBusiness()->where('sec_start_datetime', '>=', $now->toDateTimeString())->select('sec_id')->get();
                $futureServicebookings = StaffEventSingleService::OfBusiness()->where('sess_start_datetime', '>=', $now->toDateTimeString())->select('sess_id')->get();
                if($futureClassbookings->count() || $futureServicebookings->count()){
                    $query = StaffEventResource::where('serc_res_id', $res->id);
                    if(!count($futureClassbookings))
                        $query->where('serc_event_type', '!=', 'App\StaffEventClass');
                    else if(!count($futureServicebookings)){
                        $futureClassbookings = $futureClassbookings->pluck('sec_id')->toArray();
                        $query->where('serc_event_type', '!=', 'App\StaffEventSingleService');
                    }
                    else
                        $futureServicebookings = $futureServicebookings->pluck('sess_id')->toArray();

                    $query->where(function($query) use($futureClassbookings, $futureServicebookings){
                                if(count($futureClassbookings)){
                                    $query->where(function($query) use($futureClassbookings){
                                        $query->where('serc_event_type', 'App\StaffEventClass')->whereIn('serc_event_id', $futureClassbookings);
                                    });
                                }

                                if(count($futureServicebookings)){
                                    $query->orWhere(function($query) use($futureServicebookings){
                                        $query->where('serc_event_type', 'App\StaffEventSingleService')->whereIn('serc_event_id', $futureServicebookings);
                                    });
                                }
                            })
                            ->delete();
                }*/
            });
            static::deleted(function(){
                if(!Resource::where('res_business_id',Session::get('businessId'))->exists())
                    Session::forget('ifBussHasResources');
            });
        }
    /*
    **end: EVENTS
    */
}
