<?php

namespace App\Models;

use Webpatser\Uuid\Uuid;

trait UuidsTrait
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
