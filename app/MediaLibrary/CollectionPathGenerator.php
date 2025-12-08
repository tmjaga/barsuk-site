<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CollectionPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $model = strtolower(class_basename($media->model_type));

        return "media/{$model}/{$media->collection_name}/";
    }

    public function getPathForConversions(Media $media): string
    {
        $model = strtolower(class_basename($media->model_type));

        return "media/{$model}/{$media->collection_name}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        $model = strtolower(class_basename($media->model_type));

        return "media/{$model}/{$media->collection_name}/responsive/";
    }
}
