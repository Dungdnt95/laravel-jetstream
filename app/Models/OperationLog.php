<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

/**
 * @property int $id
 * @property string $operation_log_datetime
 * @property string $screen_name
 * @property int $user_id
 * @property string $operation_name
 * @property int $operation_type
 * @property string $operation_value
 * @property string $created_at
 * @property string $updated_at
 */
class OperationLog extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    /**
     * @var array
     */
    protected $fillable = ['operation_log_datetime', 'screen_name', 'user_id', 'operation_name', 'operation_type', 'operation_value', 'created_at', 'updated_at'];

    protected $casts = [
        'operation_value' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
