<?php

namespace App\Http\Requests\Session;

use App\Models\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userId = auth()->id();
        $sessionId = $this->route('session.update');
        $cacheKey = "authorize:$userId:$sessionId"; // Create a cache key

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($userId, $sessionId) {
            $session = Session::find($sessionId);
            dd($userId, $sessionId, $session);

            return $session && $userId === $session->user_id;
        });
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // add some validation that makes sure you have at least one of the following:
        // starting_rank, rank, starting_division, division
        return [
            'starting_rank' => 'nullable|string',
            'rank' => 'nullable|string',
            'starting_division' => 'nullable|integer',
            'division' => 'nullable|integer',
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (!$this->filledAny(['starting_rank', 'rank', 'starting_division', 'division'])) {
                $validator->errors()->add('fields', 'At least one of the following fields must be present: starting_rank, rank, starting_division, division.');
            }
        });
    }
    protected function filledAny(array $keys): bool
    {
        foreach ($keys as $key) {
            if ($this->filled($key)) {
                return true;
            }
        }
        return false;
    }
}
