<?php
namespace App\Http\Controllers;

use App\HelperService\Helpers;
use Laravel\Lumen\Routing\Controller;
use App\Models\User;
use App\Models\Config as UserConfig;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Carbon\Carbon;

use function PHPUnit\Framework\throwException;

class AuthController extends Controller
{
    private $helpers;
    private $validator_msg = [
        'username.required' => 'Username can not be empty.',
        'password.required' => 'Password can not be empty.',
        'email.required' => 'Email can not be empty.',
        'current_password.required' => 'Current password can not be empty.',
        'new_password.required' => 'New password can not be empty.',
    ];
    private $validator_rules = [
        'username' => ['required','string','min:3','max:50'],
        'email' => ['email','required'],
        'password' => ['string','required','min:8'],
        'confirm_password' => ['min:8','string'],
        'new_password' => ['required_with:confirm_password','same:confirm_password','string','min:8','regex:/^.*(?=.{1,})(?=.*[a-z])(?=.*[A-Z]|[\x{0080}-\x{024F}])(?=.*[0-9])(?=.*[\d\x]).*$/u'],
    ];


    public function __construct(Helpers $helpers){
        $this->middleware('auth:api', ['except' => ['login','refresh','logout','register']]);
        $this->helpers = $helpers;

    }

    protected function createNewResponse(){
        return [
            'token_type' => 'cookie',
            'exp_in' => JWTFactory::getTTL() * 60,
        ];
    }

    public function register(Request $request){
        if(User::count() >= Config::get('auth.MAX_REGISTERED_USERS')) return $this->helpers->response(false, $this->helpers->badRequest, null, "Max allowed Users are reached");


        $register_user_rule = $this->validator_rules['username'];
        array_push($register_user_rule, 'unique:users');

        $validator = Validator::make($request->all(), [
            'username' => $register_user_rule,
            'email' => $this->validator_rules['email'],
            'new_password' => $this->validator_rules['new_password'],
            'confirm_password' => $this->validator_rules['confirm_password'],
        ], $this->validator_msg);

        if ($validator->fails()) return $this->helpers->response(false, $this->helpers->badRequest, null, $validator->errors()->first());

        $user = new User();
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = app('hash')->make($request->new_password, ['rounds' => 12]);
        $user->save();

        $config = new UserConfig();
        $config->config = "{sections:[]}";
        $user->config()->save($config);

        if(file_exists($this->helpers->imgPath($user->id))){
            $files = scandir($this->helpers->imgPath($user->id));
            $files = array_slice($files, 2);
            foreach($files as $file){
                if(is_file($file)) {
                    unlink($file);
                }
            }
        } else mkdir($this->helpers->imgPath($user->id));

        return $this->helpers->response(true, $this->helpers->created, [ "user" => $user ], null);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => $this->validator_rules['username'],
            'password' => $this->validator_rules['password'],
        ], $this->validator_msg);

        if ($validator->fails()) return $this->helpers->response(false, $this->helpers->badRequest, null, $validator->errors()->first());

        $credentials = $request->only("username", "password");

        if (! JWTAuth::attempt($credentials)) return $this->helpers->response(false, $this->helpers->unauthorized, null, "Incorrect username or password");

        $remember = ($request->get('remember') === true);

        $token = $this->helpers->makeToken(JWTAuth::user()->id, $remember);
        $cookie = $this->helpers->makeTokenCookie($token, $remember);

        return $this->helpers->response(true, $this->helpers->accepted, $this->createNewResponse($remember), null)->withCookie($cookie);
    }

    public function logout(Request $request){
        return $this->helpers->response(true, $this->helpers->accepted, "Logged out", null)->withCookie($this->helpers->forgetTokenCookie());
    }

    public function refresh(Request $request){
        try{
            if(! $old_token = $this->helpers->getCookieToken($request)) return response('Unauthorized. Missing token.', 401);

            $payload = JWTAuth::getJWTProvider()->decode($old_token);//JWTAuth::setToken($old_token)->getPayload();
            $remember = ($payload['remember_me'] === true);
            $iat = $payload['iat'];
            $sub = $payload['sub'];
            $timeToAdd = $remember === true ? Config::get('jwt.cookie_remember_ttl') : Config::get('jwt.cookie_ttl');

            if(Carbon::createFromTimestampUTC($iat)->addMinutes($timeToAdd)->isPast()) return $this->helpers->response(true, $this->helpers->badRequest, [ 'exp_in' => -1 ], "Token not longer able to refresh.")->withCookie($this->helpers->forgetTokenCookie());
            $token = $this->helpers->makeToken($sub, $remember);

            $cookie = $this->helpers->makeTokenCookie($token, $remember);

            return $this->helpers->response(true, $this->helpers->accepted, $this->createNewResponse($remember), null)->withCookie($cookie);
        } catch(Exception $e){
            return $this->helpers->response(true, $this->helpers->badRequest, null, null);
        }
    }


    public function updateProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => $this->validator_rules['username'],
            'email' => $this->validator_rules['email'],
            'password' => $this->validator_rules['password'],
        ], $this->validator_msg);

        if ($validator->fails()) return $this->helpers->response(false, $this->helpers->badRequest, null, $validator->errors()->first());

        if(!app('hash')->check($request->password, JWTAuth::user()->password)) return $this->helpers->response(false, $this->helpers->badRequest, null, "Wrong Password");

        try{
            JWTAuth::user()->username = $request->get('username');
            JWTAuth::user()->email = $request->get('email');
            JWTAuth::user()->save();
            return $this->helpers->response(true, $this->helpers->updated, [ 'user' => JWTAuth::user() ], null);
        } catch(Exception $e) {
            $this->helpers->response(false, $this->helpers->internalError, null, null);
        }
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'current_password' => $this->validator_rules['password'],
            'new_password' => $this->validator_rules['new_password'],
            'confirm_password' => $this->validator_rules['confirm_password'],
        ], $this->validator_msg);

        if ($validator->fails()) return $this->helpers->response(false, $this->helpers->badRequest, null, $validator->errors()->first());

        if(!app('hash')->check($request->current_password, JWTAuth::user()->password)) return $this->helpers->response(false, $this->helpers->badRequest, null, "Wrong Password");

        try{
            JWTAuth::user()->password = app('hash')->make($request->new_password, ['rounds' => 12]);
            JWTAuth::user()->save();
            return $this->helpers->response(true, $this->helpers->updated, [ 'user' => JWTAuth::user() ], null);
        } catch(Exception $e) {
            $this->helpers->response(false, $this->helpers->internalError, null, null);
        }
    }
}
