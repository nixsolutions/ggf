<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

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
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'league.name' => 'required|min:3|max:255',
            'league.logo' => 'image|mimes:jpeg,bmp,png,jpg|max:10000'
        ];
    }
}
