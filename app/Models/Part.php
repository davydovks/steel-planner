<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Part extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['quantity'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'order_id',
        'position',
        'quantity',
        'weight',
        'profile',
    ];

    /**
     * Get structures that contain the part
     */
    public function structures()
    {
        return $this->belongsToMany(related: Structure::class)->withPivot('quantity');
    }

    /**
     * Get the order for the part
     */
    public function order()
    {
        return $this->belongsTo(related: Order::class);
    }

    /**
     * Get part quantity within order
     */
    public function getQuantityAttribute()
    {
        return $this->structures()->sum(DB::raw('part_structure.quantity * structures.quantity'));
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('position', 'like', "%$search%")
                    ->orWhere('profile', 'like', "%$search%");
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
