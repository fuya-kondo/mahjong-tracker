<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 認証は後で
    }

    public function rules(): array
    {
        return [
            'league_id' => ['required', 'integer', 'exists:leagues,id'],
            'season_id' => ['required', 'integer', 'exists:seasons,id'],
            'rule_id'   => ['required', 'integer', 'exists:rules,id'],
            'play_date' => ['required', 'date'],
            'round'     => ['required', 'integer', 'min:1'],

            'players' => ['required', 'array', 'size:4'],
            'players.*.user_id' => ['required', 'integer', 'exists:users,id'],
            'players.*.seat'    => ['required', 'integer', 'between:1,4'],
            'players.*.score'   => ['required', 'integer'],
            'players.*.mistake_count' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'players.size' => '対局は必ず4人で登録してください。',
        ];
    }
}
