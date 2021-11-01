<?php
namespace App\HelperService;

use Exception;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class Helpers
{
    public $accepted = [ 'msg' => 'Accept', 'code' => 200];
    public $created = [ 'msg' => 'Created', 'code' => 201];
    public $updated = [ 'msg' => 'Updated', 'code' => 204];
    public $badRequest = [ 'msg' => 'Bad Request', 'code' => 400];
    public $unauthorized = [ 'msg' => 'Unauthorized', 'code' => 401];
    public $internalError = [ 'msg' => 'Internal Error', 'code' => 500];

    private function isSecure(): bool{
        $secure = Config::get('app.secure');
        if(!is_bool($secure)){
            $secure = $secure === 'https' ? true : false;
        }
        return $secure;
    }

    public function response(bool $isSuccess, array $report, $data = null, $error = null)
    {
        return response()->json([
            'success' => $isSuccess,
            'responseCode' => $report['code'],
            'error' => $error,
            'report' => $report['msg'],
            'data' => $data
        ], $report['code'], [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    public function makeToken(int $sub, bool $remember){
        return JWTAuth::encode(JWTFactory::sub($sub)->remember_me($remember)->make());
    }

    public function makeTokenCookie($token, bool $remember = false)
    {
        $ttl = $remember === true ? Config::get('jwt.cookie_remember_ttl') : Config::get('jwt.cookie_ttl');
        return Cookie::create(
            Config::get('jwt.cookie_name'),
            Crypt::encrypt($token),
            time() + $ttl * 60,
            null,
            null,
            $this->isSecure(),
            true,
            false,
            'Strict'
        );
    }

    public function forgetTokenCookie(){
        return Cookie::create(Config::get('jwt.cookie_name'), null, -1);
    }

    public function cookieTTL(){
        return Config::get('jwt.cookie_ttl');
    }

    public function cookieRememberTTL(){
        return Config::get('jwt.cookie_remember_ttl');
    }

    public function getCookieToken(Request $request): String {
        $cookie = $request->cookie(Config::get('jwt.cookie_name'));

        if(!isset($cookie)) return false;

        return Crypt::decrypt($cookie);
    }

    public function validateConfig(array $config) {
        try {
            if(is_array($config['sections'])){
                foreach($config['sections'] as $s_idx=>$section){
                    if(is_array($section)) {
                        foreach($section as $i_idx=>$item){
                            if(!isset($item['name'])  || !is_string($item['name'])  ) return "Missing 'Name' in Section" . ($s_idx + 1) . "- Item " . ($i_idx + 1);
                            if(!isset($item['url'])   || !is_string($item['url'])   ) return "Missing 'URL' in Section" . ($s_idx + 1) . "- Item " . ($i_idx + 1);
                            if(!isset($item['image']) || !is_string($item['image']) ) return "Missing 'Image' in Section" . ($s_idx + 1) . "- Item " . ($i_idx + 1);
                            if(!isset($item['color']) || !is_string($item['color']) ) return "Missing 'Color' in Section" . ($s_idx + 1) . "- Item " . ($i_idx + 1);
                        }
                    }
                }
            }
            return true;
        } catch(Exception $e) { return "Internal Error while validating Config"; }
    }

    public function imgPath($string){
        return app()->storagePath("app/" . $string);
    }
}