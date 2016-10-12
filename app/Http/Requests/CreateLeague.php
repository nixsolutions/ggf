<?php

namespace App\Http\Requests;

/**
 * Class CreateLeague
 * @package App\Http\Requests
 */
class CreateLeague extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'league.name' => 'required|min:3',
            'league.logoPath' => 'asset_exists'
        ];
    }
}
