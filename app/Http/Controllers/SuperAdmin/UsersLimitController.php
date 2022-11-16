<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserLimit;

class UsersLimitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $length = 25;
        if($search){
            $userLimits = UserLimit::where(function($query) use($search){
                            $query->orWhere('maximum_users', 'like', "%$search%")
                                  ->orWhere('price', 'like', "%$search%");
                        })
                        ->orderBy('id', 'desc')
                        ->paginate($length);
        }
        else{
            $userLimits = UserLimit::orderBy('id', 'desc')->paginate($length);
        }
        
        return view('super-admin.users-limit.index',compact('userLimits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super-admin.users-limit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'maximum_users' => 'numeric|integer|required',
            'price' => 'required'
        ]);
        $requestData = $request->all();
        try{
            UserLimit::create($requestData);
            return redirect()->route('users-limit.index')->with('message', 'success|User Limit has been created successfully.');
        } catch (\Throwable $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($users_limit)
    {
        $userLimit = UserLimit::find($users_limit);
        return view('super-admin.users-limit.show',compact('userLimit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($users_limit)
    {
        $userLimit = UserLimit::find($users_limit);
        return view('super-admin.users-limit.edit',compact('userLimit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $users_limit)
    {
        $this->validate($request,[
            'maximum_users' => 'numeric|integer|required',
            'price' => 'required'
        ]);
        $requestData = $request->all();
        $userLimit = UserLimit::find($users_limit);
        try{
            $userLimit->update($requestData);
            return redirect()->route('users-limit.index')->with('message', 'success|User Limit has been updated successfully.');
        } catch (\Throwable $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserLimit::where('id',$id)->delete();
        return redirect()->route('users-limit.index')->with('message', 'success|Data has been deleted successfully.');
    }
}
