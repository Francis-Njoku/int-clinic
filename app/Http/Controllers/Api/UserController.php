<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWorkerResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Mail\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /*
    * Check identifier field
    */
    protected function credentials($identifier)
    {
        if(is_numeric($identifier)){

            return "phone"; //['phone'=>$request->get('email'),'password'=>$request->get('password')];
        }
        elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {

            return "email"; //['email' => $request->get('email'), 'password'=>$request->get('password')];
        }

        return "name"; //['username' => $request->get('email'), 'password'=>$request->get('password')];
    }
    /***
     * Generate Identity
     * @param No params
     * @return unique Identity
     */
    private function generateIdentity()
    {
        $randomNumber = random_int(10000000000000, 99999999999999);
        if (User::where('identity', '=', $randomNumber)->exists()) {
            return $this->generateIdentity();
         }
         else{
            return $randomNumber;
         }
    }

      /***
     * Generate Identity
     * @param No params
     * @return unique Identity
     */
    private function generateUser()
    {
        $randomNumber = random_int(100000, 999999);
        if (User::where('name', '=', $randomNumber)->exists()) {
            return $this->generateIdentity();
         }
         else{
            return $randomNumber;
         }
    }
    private function isValidTimezoneId($usertimezone) {
        try{
            new DateTimeZone($usertimezone);
            return true;
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Wrong Timezone, please try again'
            ], 500);
        }
        return true;
    }
    private function getTimeZone($getZome)
    {
        $usertimezone="Africa/Lagos"; 

        date_default_timezone_set($usertimezone); 

        //new date and time
        $ndate= new datetime();
        //split into date and time seperate
        $nndate =$ndate->format("Y-m-d");
        $nntime= $ndate->format("H:i:S");
        //here you can test it
        echo $nndate.'<br/>';
        echo $nntime;

    }
    /***
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'firstName' => 'required',
                    'lastName' => 'required',
                    'phone' => 'required|unique:users,phone',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->firstName.$this->generateUser(),
                'email' => $request->email,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'phone' => $request->phone,
                'identity' => $this->generateIdentity(),
                'status' => 'approved',
                'group_id' => $request->group_id,
                'password' => Hash::make($request->password)
            ]);
            
            // Send email to new user
            event(new Registered($user));

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'identifier' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            
            $getField = $this->credentials($request->identifier);

            if (!Auth::attempt([$getField => $request->identifier, 'password' => $request->password])) {
                return response()->json([
                    'status' => false,
                    'message' => $getField.' & Password does not match with our record.',
                ], 401);
            }


            $user = User::where($getField, $request->identifier)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResource
    {
        auth()->user()->tokens()->delete();

        return response()->success([], 'logged out', 200);
    }
 
    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $verify = User::where('email', $request->all()['email'])->exists();

        if ($verify) {
            $verify2 = DB::table('password_resets')->where([
                ['email', $request->all()['email']]
            ]);

            if ($verify2->exists()) {
                $verify2->delete();
            }

            $token =  random_int(100000, 999999);
            $password_reset = DB::table('password_resets')->insert([
                'email' => $request->all()['email'],
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            if ($password_reset) {
                Mail::to($request->all()['email'])->send(new ResetPassword($token));

                return new JsonResponse(
                    [
                        'success' => true,
                        'message' => "Please check your email for a 6 digit pin"
                    ],
                    200
                );
            } else {
                return new JsonResponse(
                    [
                        'success' => false,
                        'message' => "This email does not exist"
                    ],
                    400
                );
            }
        }
    }

    /**
     * @param VerifyPin
     * @return JsonResponse
     * @throws ValidationException
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $check = DB::table('password_resets')->where([
            ['email', $request->all()['email']],
            ['token', $request->all()['token']],
        ]);

        if ($check->exists()) {
            $difference = Carbon::now()->diffInSeconds($check->first()->created_at);
            if ($difference > 3600) {
                return new JsonResponse(['success' => false, 'message' => "Token Expired"], 400);
            }
            $delete = DB::table('password_resets')->where([
                ['email', $request->all()['email']],
                ['token', $request->all()['token']],
            ])->delete();

            $user = User::where('email', $request->email);
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $token = $user->first()->createToken('myapptoken')->plainTextToken;

            return new JsonResponse(
                [
                    'success' => true,
                    'message' => "Your password has been reset",
                    'token' => $token
                ],
                200
            );

            /*
            return new JsonResponse(
                [
                    'success' => true,
                    'message' => "You can now reset your password"
                ],
                200
            );*/
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => "Invalid token"
                ],
                401
            );
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */   
    public function listUsers(Request $request)
    {
        /*
        $user = $request->user();
        if ($user->isAdmin == false) {
            return abort(403, 'Unauthorized action.');
        }*/
        return UserResource::collection(User::paginate(10));
    }

    public function listUsersByWorker(Request $request)
    {
        if ($request->get('s'))
        {
            return UserWorkerResource::collection(
                DB::table('users')
                ->join('worker_clients', 'users.id','=', 'worker_clients.worker_id')
                ->select('users.*')
                ->where('worker_clients.worker_id','=',Auth::id())
                ->where(function($query) use ($request){
                    $query->where('users.firstName', 'like', '%'.$request->get('s').'%')
                    ->orWhere('users.lastName', 'like', '%'.$request->get('s').'%')
                    ->orWhere('users.phone', 'like', '%'.$request->get('s').'%')
                    ->orWhere('users.name', 'like', '%'.$request->get('s').'%')
                    ->orWhere('users.email', 'like', '%'.$request->get('s').'%');
                })
                ->paginate(10)
            );
        }
        else{
            return UserWorkerResource::collection(
                DB::table('users')
                ->join('worker_clients', 'users.id','=', 'worker_clients.worker_id')
                ->select('users.*')
                ->where('worker_clients.worker_id','=',Auth::id())
                ->paginate(10)
            );
        }
        
    }
}
