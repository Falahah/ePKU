<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalStaff extends Model
{
    protected $primaryKey = 'msID';

    protected $fillable = [
        'msID',
        'medStaffId',
        'name',
        'gender',
        'email',
        'phone_number',
        'position',
        'department_id',
        'unit_id',
        'active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    }
