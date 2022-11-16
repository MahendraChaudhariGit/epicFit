<?php

namespace App\Http\Controllers\Setings\Client;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class ClientController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $client = Client::paginate(15);

        return view('Settings.client.index', compact('client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('Settings.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //$this->validate($request, ['first_name' => 'required', 'last_name' => 'required', 'client_status' => 'required', 'email' => 'required', 'phone' => 'required', 'referral_type' => 'required', 'goals' => 'required' ]);

        Client::create($request->all());

        Session::flash('flash_message', 'Client added!');

        return redirect('settings/client');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);

        return view('Settings.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);

        return view('Settings.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['first_name' => 'required', 'last_name' => 'required', 'client_status' => 'required', 'email' => 'required', 'phone' => 'required', 'referral_type' => 'required', 'referral_name' => 'required', 'goals' => 'required', 'client_notes' => 'required', ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());

        Session::flash('flash_message', 'Client updated!');

        return redirect('settings/client');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Client::destroy($id);

        Session::flash('flash_message', 'Client deleted!');

        return redirect('settings/client');
    }

}
