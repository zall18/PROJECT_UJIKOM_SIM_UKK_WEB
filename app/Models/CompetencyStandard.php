<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyStandard extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function competency_elements()
    {
        return $this->hasMany(CompetencyElement::class);
    }

    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    public function assessor()
    {
        return $this->belongsTo(Assessor::class);
    }

    public function standardAssessor()
    {
        return $this->belongsToMany(Assessor::class, 'standard_assessors', 'competency_standard_id', 'assessor_id');
    }
}
