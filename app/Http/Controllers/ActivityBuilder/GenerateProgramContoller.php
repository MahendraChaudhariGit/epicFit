<?php

namespace App\Http\Controllers\ActivityBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use Input;
use Auth;

use App\Http\Traits\ActivityBuilderTrait;
use App\Http\Traits\HelperTrait;

use App\AbClientPlan;

class GenerateProgramContoller extends Controller{
    use HelperTrait, ActivityBuilderTrait;

    /** Set cookie for filter */
    private $cookieSlug = 'generatePrograms';


    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function edit(){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-generate-program'))
            abort(404);

        $exerciseData = $this->getExercisesOptions();
        $where['businessId'] = Session::get('businessId');
        $where['plan_type'] = 5;
        $where['clientId'] = 0;
        $planExistOrNot = AbClientPlan::where($where)->get();

        if(!$planExistOrNot->count()){
           $autoSavePlan = AbClientPlan::where('businessId', 0)->where('plan_type',5)->first();
           $newRow1 = $autoSavePlan->replicate();
           $newRow2 = $autoSavePlan->replicate();
           $newRow1->businessId = $where['businessId'];
           $newRow1->status = 'incomplete';
           $newRow1->gender = 1;
           $newRow1->save();
           $newRow2->businessId = $where['businessId'];
           $newRow2->status = 'incomplete';
           $newRow2->gender = 2;
           $newRow2->save();
        }
        return view('ActivityBuilder.GenerateProgram.edit', compact('exerciseData'));
    }

    /**
     * Show generator section according to gender.
     * @param  gender
     * @return client plan id
     */
    public function show(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-generate-program'))
            abort(404);
        $response['status'] = 'error';
        $business_id = Session::get('businessId');
        $gender = (int)$request->gender;
        $where['gender'] = (int)$request->gender;
        $where['plan_type'] = 5;
        $where['clientId'] = 0;
        $where['businessId'] = $business_id;
        
        $generateProgramId = AbClientPlan::where($where)->pluck('id')->first();
        if($generateProgramId){
            $response['clientPlanId'] = $generateProgramId;
            $response['status'] = 'success';
        }
        return json_encode($response);
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request){
        return json_encode($msg);
    }

}
