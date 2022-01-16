<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;

class UserController extends  BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),500);       
        }
   
        $input = $request->all();
    
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('WE_1')->accessToken;
        $success['name'] =  $user->first_name.' '.$user->last_name;
   
        return $this->sendResponse($success, 'User register successfully.',200);
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            // dd( $user->createToken('WE_1')->accessToken);
            $success['token'] =  $user->createToken('WE_1')->accessToken;
            $success['name'] =  $user->first_name.' '.$user->last_name ;
   
            return $this->sendResponse($success, 'User login successfully.',200);
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'],401);
        } 
    }

    public function list(Request $request)
    {
        $get_first = function($x){
            return $x[0];
        };

        $headers = array_map($get_first, $request->headers->all());
        
        // $validator = Validator::make($headers,[
        //     'accept'=>'required',
        //     'app_version'=>'required',
        //     'last_sync_date'=>'required',
        //     'device_type'=>'required'
        // ]);
        // if($validator->fails()){
        //     return  response()->json(['Validation errors' => $validator->errors()], 500);     
        // }

        // $data = ['accept'=>$request->header('accept'),
        //          'app_version'=>$request->header('app_version'),
        //          'last_sync_date'=>$request->header('last_sync_date'),
        //          'device_type'=>$request->header('device_type')
        //         ];

    
        return  response()->json('OK, MESSAGE!', 201) ->withHeaders([
                                                                'accept' => $headers['accept'],
                                                                'app_version' => $headers['app-version'],
                                                                'device_type' => $headers['device-type'],
                                                                'last_sync_date'=> Carbon::now()->format('m-d-Y')
                                                            ]);
    }
}
