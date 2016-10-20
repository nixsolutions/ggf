<?php

namespace App\Http\Requests\Tournament;

use App\Http\Requests\Request;
use App\Tournament;

/**
 * Class Create
 * @package App\Http\Requests\Tournament
 */
class Create extends Request
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
            'tournament.name' => 'required|min:3|max:255',
            'tournament.description' => 'min:3|max:255',
            'tournament.type' => 'in:' . implode(',', Tournament::getAvailableTypes()),
            'tournament.membersType' => 'in:' . implode(',', Tournament::getAvailableMembersType())
        ];
    }
}
