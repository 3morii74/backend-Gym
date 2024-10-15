<?php

namespace Modules\Gallary\Models;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Gallary extends Model
{
    use HasFactory;
    use ApiResponseTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['file_root', 'file_name', 'extension', 'path'];

    public function storeImage($request, $file_root, $path)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Customize as per your requirements
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move($path, $filename);
            $image = $this->create([
                'file_root' => $file_root,
                'file_name' => $filename,
                'extension' => $extension,
                'path' => asset($path . '/' . $filename),
            ]);
            return $image;
        }

        return response()->json(['error' => 'Image could not be stored'], 404);
    }

    // public function updateImage($id, $request, $file_root, $path)
    // {
    //     $image = $this->find($id);

    //     if (!$image) {
    //         return response()->json(['error' => 'Image not found'], 404);
    //     }

    //     // Validation
    //     $validator = Validator::make($request->all(), [
    //         'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Customize as per your requirements
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     if (file_exists(public_path($image->path))) {
    //         unlink(public_path($image->path));
    //     }

    //     $file = $request->file('image');
    //     $extension = $file->getClientOriginalExtension();
    //     $filename = time() . '.' . $extension;
    //     $file->move($path, $filename);

    //     $image->update([
    //         'file_root' => $file_root,
    //         'file_name' => $filename,
    //         'extension' => $extension,
    //         'path' => $path . '/' . $filename,
    //     ]);

    //     return $image;
    // }

    public function deleteImage($id = null, $name = null)
    {
        if ($id) {
            $image = $this->find($id);
        } else {
            $image = $this->where('file_name', $name)->first();
        }

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        if (file_exists(public_path($image->path))) {
            unlink(public_path($image->path));
        }

        $image->delete();
        return response()->json(['message' => 'Image deleted successfully'], 200);
    }

 
}
