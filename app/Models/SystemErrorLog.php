<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
 * @property int $id
 * @property string $uri
 * @property string $ip
 * @property string $user_agent
 * @property string $message
 * @property mixed $data
 * @property string $created_at
 * @property string $updated_at
 */
class SystemErrorLog extends Model
{
    use HasFactory, Sortable;

    /**
     * @var array
     */
    protected $fillable = ['uri', 'ip', 'user_agent', 'status_code', 'message', 'data', 'created_at', 'updated_at'];

    protected $casts = [
        'data' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
