<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['weight'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'order_id',
        'quantity',
    ];

    /**
     * Get the order for the structure
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get parts for the structure
     */
    public function parts()
    {
        return $this->belongsToMany(Part::class)->withPivot('quantity');
    }

    /**
     * Get structure weight
     */
    public function getWeightAttribute()
    {
        return $this->parts()->get()->reduce(function ($totalWeight, $part) {
            $quantity = $part->pivot->quantity ?? 0;
            $weight = $quantity * $part->weight;

            return $totalWeight + $weight;
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->join('orders', 'structures.order_id', '=', 'orders.id')
                ->where('structures.name', 'like', "%$search%")
                ->orWhere('orders.name', 'like', "%$search%");
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
