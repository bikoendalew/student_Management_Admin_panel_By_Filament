<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\Guard;

class Student extends Model
{
    use HasFactory;
    protected $fillable=['name','age','address','student_id','standard_id','vitals'];

    protected $casts=[
        'vitals'=>'json'
    ];

    public function standard():BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function guardians():BelongsToMany{
        return $this->belongsToMany(Guardian::class);
    }

    public function certificates():HasMany{
        return $this->hasMany(CertificateStudent::class);
    }
}
