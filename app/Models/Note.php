<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject', 'note', 'attachments', 'task_id',
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getAttachmentsAttribute($value)
    {
        $attachments = explode(',', $value);
        $completePaths = [];

        foreach ($attachments as $attachment) {
            $completePaths[] = url('uploads/' . $attachment);
        }
        return $completePaths;
    }
}
