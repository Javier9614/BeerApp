<?php

namespace App\Manager;

use Mael\InterventionImageBundle\MaelInterventionImageManager;

class BeerManager{
    protected $imageManager;

    public function __construct(MaelInterventionImageManager $imageManager){

        $this->imageManager = $imageManager;

    }

    public function updateImage($waterMark, $imageFile){

        $this->imageManager->make($imageFile)->insert($waterMark)->save($imageFile);

    }
}
 