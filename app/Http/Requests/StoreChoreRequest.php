<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreChoreRequest extends FormRequest
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
            'due_date' => 'required|date',
            'assigned_users' => 'required|array|min:1',
            'assigned_users.*' => 'exists:users,id',
        ];
    }
}