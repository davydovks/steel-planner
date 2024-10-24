<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attibutes that are mass assignable
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'address',
        'TIN',
    ];

    /**
     * Get customer type
     */
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get total weight of customer's orders
     */
    public function getWeightAttribute()
    {
        return $this->orders()
            ->get()
            ->reduce(
                fn ($totalWeight, $order) => $totalWeight + $order->weight
            );
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%")
                    ->orWhere('TIN', 'like', "%$search%");
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
