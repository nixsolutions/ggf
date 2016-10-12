<?php

namespace App\Validation;

/**
 * Class AssetExistsValidator
 * @package App\Validation
 */
class AssetExistsValidator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return string
     */
    public function validate($attribute, $value, $parameters)
    {
        return realpath(public_path() . DIRECTORY_SEPARATOR . $value);
    }
}