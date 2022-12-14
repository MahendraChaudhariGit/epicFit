<?php

namespace App\Events\Frontend\Auth;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

use Session;

/**
 * Class UserLoggedOut
 * @package App\Events\Frontend\Auth
 */
class UserLoggedOut extends Event
{
    use SerializesModels;

    /**
     * @var $user
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        Session::flush();
    }
}