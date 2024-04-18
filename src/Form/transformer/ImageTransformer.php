<?php

namespace App\Form\transformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
class ImageTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value)
    {
        // Si le nom de fichier est fourni, créez une instance de File
        if ($value) {
            return new File($value);
        }
        return null;
    }

}