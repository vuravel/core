<?php

namespace Vuravel\Core\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class NotTranslatable extends Exception
{
    public static function make(string $key, Model $model)
    {
        $translatable = implode(', ', $model->getTranslatableAttributes());

        return new static("Cannot translate attribute `{$key}` as it's not one of the translatable attributes: `$translatable`");
    }
}
