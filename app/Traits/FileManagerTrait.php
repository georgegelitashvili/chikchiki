<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Image;

trait FileManagerTrait
{
    protected function getPath()
    {
        $website = app(\Hyn\Tenancy\Environment::class)->tenant();
        $tenantid = $website->id;

        $path = 'uploads/' . $tenantid;

        return $path;
    }

    protected function getRandomName($file)
    {
        $random = Str::random(8);
        $filename = date("Y.m.d_H.i.s") . '_' . $random . '_' . $file->getClientOriginalName();

        return $filename;
    }

    protected function uploadFile($file, $disk = null)
    {
        if (!$file) return null;

        $filename = $this->getRandomName($file);

        $uploadpath = $this->getPath();

        if ($disk === 'certificates') {
            $serverpath = $file->storeAs($uploadpath, $filename, 'certificates');
        } else {
            $serverpath = $file->storeAs($uploadpath, $filename, 'public');
        }


        return $filename;
    }

    protected function uploadImage($file, $data)
    {
        if (!$file) return null;

        $filename = $this->getRandomName($file);
        $uploadpath = $this->getPath() . '/' . $filename;

        $image = Image::make($file->path());

        $maxWidth = $data['maxWidth'] ?? null;
        $maxHeight = $data['maxHeight'] ?? null;

        if ($maxWidth !== null && $maxHeight !== null) {
            $image->height() / $maxWidth > $image->width() / $maxHeight ? $maxWidth = null : $maxHeight = null;
        }

        if ($maxWidth || $maxHeight) {
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        Storage::disk('public')->put($uploadpath, $image->stream());

        return $filename;
    }

    protected function deleteFile($filename, $disk = null)
    {
        $filepath = $this->getPath();

        if ($disk === 'certificates') {
            Storage::disk('certificates')->delete($filepath . '/' . $filename);
        } else {
            Storage::disk('public')->delete($filepath . '/' . $filename);
        }
    }
}
