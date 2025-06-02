<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}
