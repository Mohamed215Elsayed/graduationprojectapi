<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use DateTime;//
// use DateInterval;
// use \PrettyTable\PrettyTable;

class AdminController extends Controller
{
    //
    public function adminregister(REQUEST $request){
        $admin=Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            ]);
        if($admin){
            return response()->json([$admin,'status'=>true]);
        }
        else{
        return response()->json(['status'=>false]);
        }
    }

    // public function adminlog(REQUEST $request){
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth()->guard('admin_api')->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
    //     // $cookie = cookie('token', $token, 60);

    //     // $token = $request->cookie('token');

    //     return $token;

    // }

    public function adminlog(Request $request)
{
    $credentials = request(['email', 'password']);

    if (! $token = auth()->guard('admin_api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    //Store the token in a cookie with a name of "token" and an expiration time of 1 hour
    $cookie = cookie('token', $token, 60);

    //Return a response with the token and cookie attached
    return response()->json(['token' => $token])->cookie($cookie);



}



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminme()
    {
        return response()->json(auth()->guard('admin_api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminlogout()
    {
        auth()->guard('admin_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


}
