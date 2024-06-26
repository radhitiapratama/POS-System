<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sale_return";
    protected $guarded = ["id"];

    public function sale_details(): BelongsTo
    {
        return $this->belongsTo(SaleDetails::class, 'sale_detail_id', 'id');
    }
}
