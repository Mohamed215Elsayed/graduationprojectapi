<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    //
    public function adminregister(REQUEST $request){
        // $is_admin = $request->has('is_admin') ? $request->is_admin : true;
        $is_admin = $request->has('is_admin') ? ($request->is_admin ? 1 : 0) : 1;
        $admin=Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            // 'is_admin'=>$request->is_admin
            'is_admin' => $is_admin
            ]);
        if($admin){
            return response()->json([$admin,'status'=>true]);
        }
        else{
        return response()->json(['status'=>false]);
        }
    }
    //is_admin,is_doctor,is_patient,is_hospital
//     public function adminlog(Request $request)
// {
//     $credentials = request(['email', 'password']);

//     if (! $token = auth()->guard('admin_api')->attempt($credentials)) {
//         return response()->json(['error' => 'Unauthorized'], 401);
//     }

//     //Store the token in a cookie with a name of "token" and an expiration time of 1 hour
//     $cookie = cookie('token', $token, 60);

//     //Return a response with the token and cookie attached
//     return response()->json(['token' => $token])->cookie($cookie);
// }

public function adminlog(Request $request)
{
    $credentials = request(['email', 'password']);

    if (!$token = auth()->guard('admin_api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $admin = auth()->guard('admin_api')->user();

    if (!$admin || !$admin->is_admin) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json([
        'token' => $token,
        'admin' => $admin
    ]);
}
//************************************** */
    public function adminme()
    {
        return response()->json(auth()->guard('admin_api')->user());
    }


    public function adminlogout()
    {
        auth()->guard('admin_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
/*************************************** */
// use DateTime;//
// use DateInterval;
// use \PrettyTable\PrettyTable;


    // public function adminlog(REQUEST $request){
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth()->guard('admin_api')->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
    //     // $cookie = cookie('token', $token, 60);

    //     // $token = $request->cookie('token');

    //     return $token;

    // }
    //
