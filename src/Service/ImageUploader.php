<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function uploadImage(UploadedFile $file, string $subDirectory): string
    {
        $newImageName = uniqid() . '-' . $file->guessExtension();
        $destination = 'public/uploads/';//$this->getParameter('kernel.project_dir').'/public/uploads/'.$subDirectory;
        $file->move($destination, $newImageName);

        return $destination.$newImageName;
    }
}
