<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\UploadImageRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['uploadImage']
        ]);
    }

    /**
     * Upload a new image.
     *
     * @param \App\Http\Requests\File\UploadImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(UploadImageRequest $request)
    {
        $image = $request->file('image');
        $name = time() . '.' . $image->getClientOriginalExtension();

        try {
            $image->move('uploads/images/' . $request->folder, $name);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        $file = File::create([
            'type' => File::TYPE_IMAGE,
            'folder' => $request->folder,
            'name' => $name
        ]);

        return $this->wrapResponse(new FileResource($file));
    }
}
