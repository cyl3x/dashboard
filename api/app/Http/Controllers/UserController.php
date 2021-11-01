<?php
namespace App\Http\Controllers;

use App\HelperService\Helpers;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    private $helpers;
    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct(Helpers $helpers){
        $this->middleware('auth:api', ['except' => ['validateConfig']]);
        $this->helpers = $helpers;
    }

    public function user(Request $request)
    {
        return $this->helpers->response(true, $this->helpers->accepted, [ 'user' => JWTAuth::user() ], null);
    }

    public function config(Request $request) {
        $config = JWTAuth::user()->config;
        return $this->helpers->response(true, $this->helpers->accepted, [ 'config' => json_decode($config['config']) ], null);
    }

    public function updateConfig(Request $request){
        $newConfig = $request->config;

        if(isset($newConfig) && (is_string($newConfig) || is_array($newConfig))){
            if(is_string($newConfig)){
                $newConfig = json_decode($newConfig);

                if(json_last_error() != JSON_ERROR_NONE){
                    return $this->helpers->response(false, $this->helpers->badRequest, null, "JSON: " . json_last_error_msg());
                }
            }

            $validate = $this->helpers->validateConfig($newConfig);
            if($validate !== true) return $this->helpers->response(false, $this->helpers->badRequest, null, "Config: " . $validate);

            $config = JWTAuth::user()->config;
            $config->config = json_encode($newConfig);
            $config->save();
            return $this->helpers->response(true, $this->helpers->updated, null, null);
        } else return $this->helpers->response(false, $this->helpers->badRequest, null, "Nothing delivered to update");
    }


    public function imgList(){
        if(!file_exists($this->helpers->imgPath(JWTAuth::user()->id))) mkdir($this->helpers->imgPath(JWTAuth::user()->id));

        $images = scandir($this->helpers->imgPath(JWTAuth::user()->id));
        $images = array_slice($images, 2);
        return $this->helpers->response(true, $this->helpers->accepted, [ 'list' => $images ], null);
    }

    public function img(Request $request){
        try{
            $path = $this->helpers->imgPath(JWTAuth::user()->id . '/' . $request->route('img'));

            if (!file_exists($path)) {
                return $this->helpers->response(false, $this->helpers->badRequest, null, "Image does not exists: " . $path);
            }

            $type = mime_content_type($path);

            $response = new BinaryFileResponse($path, 200 , ["Content-Type" => $type ]);

            return $response;
        } catch (Exception $e){
            return $this->helpers->response(false, $this->helpers->internalError, null, "Unknown Error");
        }
    }

    public function imgRemove(Request $request){
        try{
            $img = $request->get('image');
            if(!isset($img)) return $this->helpers->response(false, $this->helpers->badRequest, null, "No Imagename delivered");

            $path = $this->helpers->imgPath(JWTAuth::user()->id . '/' . $img);
            unlink($path);
            return $this->helpers->response(true, $this->helpers->updated, null, null);
        } catch (Exception $e){
            return $this->helpers->response(false, $this->helpers->internalError, null, "Cannot remove image");
        }
    }

    public function imgAdd(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg,png|max:2048'
        ], [ 'file.required' => 'No Image delivered.' ]);

        if ($validator->fails()) {
            return $this->helpers->response(false, $this->helpers->badRequest, null, $validator->errors()->first());
        }

        try{
            $image = $request->file('file');
            if(!isset($image)) $this->helpers->response(false, $this->helpers->badRequest, null, "No Image delivered");

            $image->move($this->helpers->imgPath(JWTAuth::user()->id), $image->getClientOriginalName());
            return $this->helpers->response(true, $this->helpers->updated, null, null);
        } catch (Exception $e){
            return $this->helpers->response(false, $this->helpers->internalError, null, "Cannot store image");
        }
    }
}