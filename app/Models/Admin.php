<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Traits\Apptraits;

class admin extends Authenticatable
{
    use Apptraits;
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'email',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $table = 'admins';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(self::class, 'updated_by');
    }

    public function stampCurrentAdmin(bool $creating = false): void
    {
        $actorId = Auth::guard('admin')->id();
        if (! $actorId) {
            return;
        }

        if ($creating && empty($this->created_by)) {
            $this->created_by = $actorId;
        }

        $this->updated_by = $actorId;
    }
}
