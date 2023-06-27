<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;
class HospitalController extends Controller
{
    use ApiResponseTrait;
    //##############################################
    public function index(){
        $hospitals=HospitalResource::collection(Hospital::get());
        return $this->ApiResponse($hospitals,"all done",200);
    }
 //##############################################
    public function show($id){
        $hospital=Hospital::find($id);
        if($hospital){
            return $this->ApiResponse(new HospitalResource($hospital),"all done",200);
        }
        return $this->ApiResponse(null,"hospital not found",404);
    }
   //##############################################
    public function store(REQUEST $request){
        //validation
        $validator=Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required|unique:hospitals',
            'password'=>'required',
            'contactno'=>'required'
        ]);
        if($validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }
        // $hospitals=Hospital::create($request->all());
        $hospitals=Hospital::create([
            // 'id'=>$request->id,//
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'contactno'=>$request->contactno,
        ]);
        if($hospitals){
            return $this->ApiResponse(new HospitalResource($hospitals),"hospital created successfully",201);
        }
        return $this->ApiResponse(null,"hospital not saved",400);
    }
    //##############################################
    public function update(REQUEST $request,$id){
           //validation
        $validator=Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required',
            'password'=>'required',
            'contactno'=>'required'
        ]);
        if($validator->fails()){
            return $this->ApiResponse(null,$validator->errors(),400);
        }
        $hospital=Hospital::find($id);
        if(!$hospital){
            return $this->ApiResponse(null,"hospital not found",400);
        }
        $hospital->update($request->all());
        if($hospital){
            return $this->ApiResponse(new HospitalResource($hospital),"hospital updated successfully",201);
        }
    }
    //##############################################
    public function destroy($id){
        $hospital=Hospital::find($id);
        if(!$hospital){
            return $this->ApiResponse(null, "hospital not found",404);
        }
        $hospital->delete($id);
        if($hospital){
            return $this->ApiResponse(new HospitalResource($hospital),"hospital deleted successfully",200);
        }
    }
//##############################################
    public function hospitallog(REQUEST $request){
        $credentials = request(['email', 'password']);
        if (! $token = auth()->guard('hospital_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // if (auth()->guard('hospital_api')->check()) {
        $x= response()->json(auth()->guard('hospital_api')->user());
    return response()->json(['token' => $token,'info'=>$x]);
    }
//##############################################
public function hospitallogout(REQUEST $request){
    auth()->guard('hospital_api')->logout();

    return response()->json(['message' => 'Successfully logged out']);
}
//##############################################
public function hospitalme()
{
    if (!auth()->guard('hospital_api')->check()) {
        return response()->json(null);
    }
    $hospital = auth()->guard('hospital_api')->user();
    return response()->json($hospital);
}
}