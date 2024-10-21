<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'customer_id',
        'due_date'
    ];

    /**
     * Get the order client
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get order structures
     */
    public function structures()
    {
        return $this->hasMany(Structure::class);
    }

    /**
     * Get order parts
     */
    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    /**
     * Get order weight
     */
    public function getWeightAttribute()
    {
        return $this->structures()->get()->reduce(function ($totalWeight, $structure) {
            $quantity = $structure->quantity ?? 0;
            $weight = $quantity * $structure->weight;

            return $totalWeight + $weight;
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
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
