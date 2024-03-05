<?php
namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    public function __construct(private KernelInterface $kernel)
    {
    }

    public function uploadImage(UploadedFile $file, string $subDirectory): string
    {
        $newImageName = uniqid() . '.' . $file->guessExtension();
        $destination = $this->kernel->getProjectDir().'/public/uploads/'.$subDirectory;
        $file->move($destination, $newImageName);

        return $destination.'/'.$newImageName;
    }
}
