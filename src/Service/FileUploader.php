<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileUploader
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    private Filesystem $filesystem;

    public function __construct(string $profilePicturesDirectory, SluggerInterface $slugger, Filesystem $filesystem)
    {
        $this->targetDirectory = $profilePicturesDirectory;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file, ?string $oldFilename = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // It's a good practice to handle the exception, maybe log it or re-throw it as a custom exception
            throw new \RuntimeException('Could not upload the file: '.$e->getMessage(), $e->getCode(), $e);
        }

        if ($oldFilename) {
            $this->remove($oldFilename);
        }

        return $fileName;
    }

    public function remove(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $filePath = $this->getTargetDirectory().'/'.$filename;
        if ($this->filesystem->exists($filePath)) {
            $this->filesystem->remove($filePath);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
