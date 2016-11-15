<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTeam extends FormRequest
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
            'leagueTeam.leagueId' => 'required|integer|exists:leagues,id',
            'leagueTeam.name' => 'required|min:3|max:255',
            'leagueTeam.logo' => 'image|mimes:jpeg,bmp,png,jpg|max:10000'
        ];
    }
}
