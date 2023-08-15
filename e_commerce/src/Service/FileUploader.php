<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    
    private $targetDirectory;                                           // targetDirectory correspond au chemin d'envoi des fichiers présent dans services.yaml
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        $safeFilename = $this->slugger->slug($originalFilename);        // Transforme les caractères unicode du nom du fichier en une version "safe" pour être ajouté dans l'URL
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        
        try {
            $file->move($this->getTargetDirectory(), $fileName);        // Déplace le fichier dans le répertoire où sont stockés les images d'avatar
        } catch (FileException $e) {
            
        }                                                               // Gère les exceptions dans le cas où un problème arrive durant l'upload
        
        return $fileName;                                               // Met à jour la propriété 'originaleFilename' pour stocker le nom du fichier image au lieu de son contenu
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}