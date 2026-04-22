<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inbox extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'inbox_user')->withPivot('is_manager');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
