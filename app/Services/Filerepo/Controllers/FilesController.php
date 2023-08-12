<?php

namespace App\Services\Filerepo\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Core\Image;
use App\Services\Filerepo\FileRepo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Carbon\Carbon;

class FilesController extends Controller
{

    protected $folder;
    protected $delete_url = "admin/file-repo/tmp/delete";

    function __construct()
    {
        if (request()->folder)
            $this->folder = rtrim(request()->folder, '/') . '/' . Carbon::now()->format('Y/m/d');
        else
            $this->folder = 'uncategorized/' . Carbon::now()->format('Y/m/d');
    }

    /**
     * loadDropzone -> store files
     */
    public function storeFile()
    {
        if (request()->clear == 1)
            return FileRepo::deleteOldTempFiles();

        return $this->saveFiles();
    }

    public function saveFiles($uploadFiles = null)
    {
        $files = $uploadFiles ?? (request()->file('files') ?: []);
        $preview = [];
        $config = [];
        $tmp_files_array = [];
        $session_array = [];
        foreach ($files as $key => $image) {

            // please check ini_get('upload_max_filesize')
            if ($image->getSize() == null) {
                return [
                    'error' => 'Cannot determine file size, seems like the file has exceeded upload max filesize.',
                    'initialPreviewConfig' => $config,
                    'initialPreviewAsData' => true
                ];
            }

            $tmp_file_name = $image->getClientOriginalName();
            $ext = $image->getClientOriginalExtension();
            $file_name = Str::slug(microtime() . $tmp_file_name) . "." . $ext;
            $path = $this->folder . '/' . $file_name;

            $temp_file_arr = [
                'file_name' => $tmp_file_name,
                'file_size' => $image->getSize(),
                'file_type' => $ext,
                'key' => $key,
                'tmp_file_name' => $file_name,
                'file_path' => $path,
                'mime_type' => $image->getMimeType(),
                'path' => url($this->folder . '/' . $file_name)
            ];

            array_push($tmp_files_array, $temp_file_arr);

            array_push($session_array, $temp_file_arr);

            try {
                if (!Storage::disk(env('FILESYSTEM_DRIVER', 'local'))->exists($path)) {
                    $file_type = self::getFileType($image->getMimeType());

                    $file = $image;
                    $folder = $this->folder;
                    $record = FileRepo::uploadFile(null, $file, $folder, $file_name, 0, true, request()->_form_token, 0);

                    // skip to next array key if failed to save file
                    if ($record === false) continue;

                    $config_data = [
                        'key' => $key,
                        'width' => '120px',
                        'caption' => $tmp_file_name,
                        'type' => $file_type,
                        'previewAsData' => true,
                        'size' => $image->getSize(),
                        'url' => url($this->delete_url) . '/' . $record->id . '/?_token=' . csrf_token(), //delete url
                        'path' => url($this->folder . '/' . $file_name)
                    ];

                    array_push($config, $config_data);
                    $preview[] = Storage::disk(env('FILESYSTEM_DRIVER', 'local'))->url($record->path);
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
                return [
                    'error' => $error,
                    'initialPreviewConfig' => $config,
                    'initialPreviewAsData' => true
                ];
            }
        }

        $image_data = [
            'success' => true,
            'images' => $session_array,
            'initialPreview' => $preview,
            'initialPreviewAsData' => true,
            'allowedPreviewTypes' => ['image', 'pdf'],
            'initialPreviewConfig' => $config,
            'previewFileIconSettings' => previewFileIconSettings()
        ];

        return $image_data;
    }

    public static function getFileType($mimeType)
    {

        $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];

        if ($mimeType == "application/pdf") {
            $file = "pdf";
        } elseif ($mimeType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $file = "office";
        } elseif ($mimeType == "text/plain") {
            $file = "text";
        } elseif ($mimeType == "application/octet-stream") {
            $file = "office";
        } elseif ($mimeType == "application/msword") {
            $file = "office";
        } elseif ($mimeType == "audio/wav") {
            $file = "audio";
        } elseif (!in_array($mimeType, $allowedMimeTypes)) {
            $file = "image";
        } else {
            $file = "image";
        }
        return $file;
    }


    public function uploadImage($image = null)
    {
        if ($image)
            $file = \request('image');
        else
            $file = \request('upload');

        $folder = 'uploads/' . Carbon::now()->format('Y/m/d');

        $file_name = Str::random(25) . "." . $file->getClientOriginalExtension();

        $record = FileRepo::uploadFile(null, $file, $folder, $file_name);

        $image = new Image();
        $image->name = $record->name;
        $image->size = $record->size;
        $image->path = $record->path;
        $image->type = $record->extension;
        $image->user_id = auth()->id();
        $image->save();

        FileRepo::updateFileRecord($image, $record, null);

        return [
            "uploaded" => true,
            "fileName" => $record->name,
            "path" => $record->path,
            "url" => FileRepo::url($record),
            "image_id" => $image->id
        ];
    }


    public function show($path)
    {
        $filePath = $path;

        
        if (Storage::disk('local')->exists($filePath)) {
            $file = Storage::disk('local')->get($filePath);

            return response($file, 200)
                ->header('Content-Type', Storage::disk('local')->mimeType($filePath));
        }

        return response()->json(['error' => 'File not found.'], 404);
    }

    /**
     * delete file
     */
    public function destroyFile($id)
    {
        FileRepo::delete($id);
        return ['cleared' => true];
    }
}
