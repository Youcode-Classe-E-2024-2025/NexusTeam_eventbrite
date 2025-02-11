<?php

namespace App\Core;

class Upload
{

    private array $file;
    private string $path;

    public function __construct(array $file){
        $this->file = $file;
        $this->path = __DIR__ . "/../Uploads/";

        if (!is_dir($this->path) && !mkdir($concurrentDirectory = $this->path, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }

    public function save(): ?string{
        if (!isset($this->file['tmp_name']) || !is_uploaded_file($this->file['tmp_name'])) {
            return null;
        }

        $fileExt = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']; // Allowed types for conversion
        if (!in_array($fileExt, $allowedTypes)) {
            return null;
        }

        $safeFileName = bin2hex(random_bytes(4)) . '.webp';
        $destination = $this->path . $safeFileName;

        if ($this->convertToWebP($this->file['tmp_name'], $destination)) {
            return $destination;
        }

        return null;
    }

    private function convertToWebP($source, $destination): bool{
        $img = null;
        $fileExt = strtolower(pathinfo($source, PATHINFO_EXTENSION));

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

}