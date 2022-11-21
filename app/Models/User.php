<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $type
 * @property string $email_verified_at
 * @property string $password
 * @property string $reset_password_token
 * @property string $reset_password_token_expire
 * @property string $last_login_at
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Sortable;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'type', 'email_verified_at', 'password', 'reset_password_token', 'reset_password_token_expire', 'last_login_at', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'reset_password_token',
        'reset_password_token_expire',
        'remember_token',
        'last_login_at',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $appends = [
        'edit_url', 'destroy_url',
    ];

    public function getEditUrlAttribute()
    {
        return route('admin.user.edit', $this->id);
    }

    public function getDestroyUrlAttribute()
    {
        return route('admin.user.destroy', $this->id);
    }
}
