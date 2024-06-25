<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sales";
    protected $guarded = ["id"];

    public function sale_details(): HasMany
    {
        return $this->hasMany(SaleDetails::class, 'sales_id', 'id');
    }
}
