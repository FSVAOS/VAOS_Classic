<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAPI extends Controller
{
    /**
     * Handles Authentication for LEGACY ACARS Clients.
     *
     * @param Request $request
     *
     * @return User
     * @return string
     */
    public function acarsLogin(Request $request)
    {
        // dd($request->input('email'));
        if ($request->query('format') == 'email') {
            $credentials = [
                'email'    => $request->input('email'),
                'password' => $request->input('password'), ];
            //dd($credentials);
        }
        if ($request->query('format') == 'username') {
            // do some extra work.
            $user        = User::where('username', $request->input('username'))->first();
            $credentials = [
                'email'    => $user->email,
                'password' => $request->input('password'), ];
        }
        if (Auth::validate($credentials)) {
            if ($request->query('format') == 'username') {
                $ret = json_encode(['status' => 200, 'user' => User::where('username', $request->input('username'))->first()]);
            }
            if ($request->query('format') == 'email') {
                $ret = json_encode(['status' => 200, 'user' => User::where('email', $request->input('email'))->first()]);
            }

            return $ret;
        } else {
            return json_encode(['status' => 403]);
        }
    }
}
