<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject', 'description', 'start_date', 'due_date', 'status', 'priority',
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function scopeWithFilters($query, $filters)
    {
        if (isset($filters['status'])) {
            $query->whereIn('status', $filters['status']);
        }
        if (isset($filters['due_date'])) {
            $query->where('due_date', $filters['due_date']);
        }
        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (isset($filters['notes']) && $filters['notes'] === 'true') {
            $query->has('notes');
        }

        return $query;
    }
}
