<?php

namespace App\Http\Controllers\Photo;

use App\Actions\Photos\RegisterImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Photo\StorePhotoRequest;
use Illuminate\Support\Facades\Auth;

class PhotosController extends Controller
{
    public function storeImage(StorePhotoRequest $request)
    {
        if (Auth::user()->type == 0) {
            return response(json_encode(['status' => 'error', 'message' => 'unauthorized']));
        }

        $imageName = time().md5($request->name.$request->user_id).'.'.$request->image->extension();

        $request->image->move(public_path('images'), $imageName);

        $register = RegisterImage::run($request->name, $imageName, $request->user_id, $request->status);

        if (!$register) {
            return response(json_encode(['status' => 'error', 'message' => 'internal error']));
        }

        return response(json_encode(['status' => 'success', 'message' => 'image has ben add']));
    }
}
