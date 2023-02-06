<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sortable;
    protected $fillable = ['name', 'type', 'amount', 'created_at', 'updated_at', 'deleted_at','status'];

    protected $appends = [
        'edit_url', 'destroy_url','update_status'
    ];

    public function getEditUrlAttribute()
    {
        return route('admin.product.edit', $this->id);
    }

    public function getDestroyUrlAttribute()
    {
        return route('admin.product.destroy', $this->id);
    }

    public function getUpdateStatusAttribute()
    {
        return route('admin.product.updateStatus', $this->id);
    }
}
