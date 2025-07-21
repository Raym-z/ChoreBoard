<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateChoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'frequency' => 'required|in:one-time,daily,weekly',
            'priority' => 'required|in:low,medium,high',
            // 'due_date' => 'sometimes|date',
            // 'assigned_users' => 'sometimes|array',
            // 'assigned_users.*' => 'exists:users,id',
        ];
    }
}