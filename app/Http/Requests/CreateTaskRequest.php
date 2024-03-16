<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tasks')->where(function ($query) {
                    return $query->where('subject', $this->subject);
                }),
            ],
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|in:New,Incomplete,Complete',
            'priority' => 'required|in:High,Medium,Low',
            'notes' => 'array', // Ensure notes is an array
            'notes.*.subject' => 'required|string|max:255',
            'notes.*.note' => 'nullable|string',
            'attachments' => 'array', 
            'attachments.*' => 'file',
        ];
    }
}
