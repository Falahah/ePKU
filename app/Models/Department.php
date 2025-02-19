<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $primaryKey = 'department_id';

    protected $fillable = [
        'name',
        'parent_id',
        'unit',
    ];

    public function medicalStaff()
    {
        return $this->hasMany(MedicalStaff::class, 'department_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
