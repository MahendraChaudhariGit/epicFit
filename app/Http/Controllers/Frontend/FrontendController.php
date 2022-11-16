<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Access\Traits\AuthenticatesUsers;
use App\Services\Access\Traits\RegistersUsers;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    use AuthenticatesUsers, RegistersUsers {
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        javascript()->put([
            'test' => 'it works!',
        ]);
        //dd('outer');
        return redirect()->intended($this->redirectPath());
    }

    /**
     * @return \Illuminate\View\View
     */
    public function macros()
    {
        return view('frontend.macros');
    }
}
