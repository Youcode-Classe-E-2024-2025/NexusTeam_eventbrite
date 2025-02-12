<?php

namespace App\Core;

class Upload
{

    private array $file;
    private string $path;

    public function __construct(array $file)
    {
        $this->file = $file;
        $this->path =  "App/Uploads/";

        if (!is_dir($this->path) && !mkdir($concurrentDirectory = $this->path, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }

    public function save(): string|false
    {
        if (!isset($this->file['tmp_name']) || !is_uploaded_file($this->file['tmp_name'])) {
            return false;
        }

        $fileExt = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov', 'mkv'];
        if (!in_array($fileExt, $allowedTypes)) {
            return false;
        }

        $fileName = bin2hex(random_bytes(4)) . ($this->isImage($fileExt) ? '.webp' : '.mp4');
        $destination = $this->path . $fileName;

        if ($this->isImage($fileExt)) {
            if ($this->convertToWebP($this->file['tmp_name'], $destination, $fileExt)) {
                return $destination;
            }
        } else {
            if ($this->compressVideo($this->file['tmp_name'], $destination)) {
                return $destination;
            }
        }

        return false;
    }

    private function isImage(string $fileExt): bool
    {
        return in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']);
    }

    private function convertToWebP($source, $destination, $fileExt): bool
    {
        $img = null;

        switch ($fileExt) {
            case 'jpeg':
            case 'jpg':
                $img = imagecreatefromjpeg($source);
                break;
            case 'png':
                $img = imagecreatefrompng($source);
                break;
            case 'gif':
                $img = imagecreatefromgif($source);
                break;
        }

        if (!$img) {
            return false;
        }

        $success = imagewebp($img, $destination, 60);
        imagedestroy($img);

        return $success;
    }

    private function compressVideo($source, $destination): bool
    {
        $command = "ffmpeg -i " . escapeshellarg($source) . " -vcodec libx264 -crf 28 " . escapeshellarg($destination);
        exec($command, $output, $return_var);

        return $return_var === 0;
    }

}