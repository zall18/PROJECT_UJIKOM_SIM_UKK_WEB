<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardAssessor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assessor()
    {
        return $this->belongsTo(Assessor::class, 'assessor_id');
    }
    public function competencyStandard()
    {
        return $this->belongsTo(CompetencyStandard::class, 'competency_standard_id');
    }
}
