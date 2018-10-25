<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 25.10.18
 * Time: 12:38
 */

namespace App;


use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JsonGuard extends TokenGuard
{
    public function __construct(UserProvider $provider, Request $request)
    {
        parent::__construct($provider, $request);
        $this->inputKey = 'token';
        $this->storageKey = 'token';
    }

}
