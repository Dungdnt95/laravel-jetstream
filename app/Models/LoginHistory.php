<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

/**
 * @property int $id
 * @property int $user_id
 * @property int $admin_id
 * @property string $login_ip
 * @property string $user_agent
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class LoginHistory extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'admin_id', 'login_ip', 'user_agent', 'created_at', 'updated_at', 'deleted_at'];
}
