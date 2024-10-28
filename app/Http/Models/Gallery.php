<?php

namespace App\Http\Models;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\ImageOptimizer\Image;
use FFMpeg;

class Gallery extends Model
{
    use HasFactory;
    use ApiResponseTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['file_root', 'file_name', 'extension', 'path'];

    public function storeFile($request, $file_root, $path)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'media' => 'required|file|mimes:jpeg,jpg,png,mp4,mov,avi|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('media')) {
            $file = $request->file('media');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            // Check if the file is an image or a video
            if (in_array($extension, ['jpeg', 'jpg', 'png'])) {
                // Move image to the specified path without compression
                $file->move($path, $filename);
            } elseif (in_array($extension, ['mp4', 'mov', 'avi'])) {
                // Compress video
                $videoPath = $path . '/' . $filename;
                $file->move($path, $filename); // Move video file to the specified path

                $ffmpeg = FFMpeg\FFMpeg::create();
                $video = $ffmpeg->open($videoPath);
                $video->save(new FFMpeg\Format\Video\X264(), $path . '/compressed_' . $filename); // Compress and save video
                unlink($videoPath); // Remove original uncompressed video
                $filename = 'compressed_' . $filename; // Update filename to compressed version
            }

            // Store file details in the database
            $media = $this->create([
                'file_root' => $file_root,
                'file_name' => $filename,
                'extension' => $extension,
                'path' => asset($path . '/' . $filename),
            ]);
            return $media;
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
