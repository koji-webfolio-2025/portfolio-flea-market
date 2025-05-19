<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'status',
        'shipping_postal_code',
        'shipping_address_line1',
        'shipping_address_line2',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
