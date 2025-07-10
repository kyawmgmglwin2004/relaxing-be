<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Dotenv\Validator;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Auth;



class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        $user = User::latest()->take(10)->get();
        return response()->json([
            'status'=> true,
            $user], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $existUser = User::where('email', $request->email)->first();
        if(!$existUser){ 
             $validator = validator(request()->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
            // 'role_id' => 'required',
            // 'photo' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                "status"=>"error",
                "message" => "Validation failed",
                "errors" => $validator->errors()],
                 409);
        }
        $user = new User;
        $user->email = request()->email;
        $user->name = request()->name;
        $user->phone = request()->phone;
        $user->password = request()->password;
        
        
        if(request()->file('photo')) {
            $photoPath = request()->file('photo')->store('user_photos', 'public');
            $user->photo = $photoPath;
        }else {
            $user->photo = null;
        }
        $user->save();
        return response()->json([
            'status' => 'success',
            'user' => 'Register Succeffully', 
            'user' => $user], 201);
           
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'User Already Existing',
                'uers' => $existUser
            ], 422);
        }

       

        // return response()->json(['message' => 'Register Succeffully!', $user], 201)
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json([
            'status' => 'Success',
            'message' => 'profile',
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $existUser = User::where('email', $request->email)->first();
        if(!$existUser){ 
             $validator = validator(request()->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
            // 'role_id' => 'required',
            // 'photo' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                "status"=>"error",
                "message" => "Validation failed",
                "errors" => $validator->errors()],
                 409);
        }
        $user = User::find($id);
        $user->email = request()->email;
        $user->name = request()->name;
        $user->phone = request()->phone;
        $user->password = request()->password;
        
        
        if(request()->file('photo')) {
            $photoPath = request()->file('photo')->store('user_photos', 'public');
            $user->photo = $photoPath;
        }else {
            $user->photo = null;
        }
        $user->save();
        return response()->json([
            'statur' => 'success',
            'user' => 'Profile update Succeffully', 
            'user' => $user], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'User Already Existing',
                // 'uers' => $existUser
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $existId = User::where('id', $id)->first();
        if($existId){
             $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete user Succeffully'
        ], 200); 
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Dont have user with this ID',
            ], 404);
        }
    }
}
