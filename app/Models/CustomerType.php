<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    /**
     * The attibutes that are mass assignable
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
}
