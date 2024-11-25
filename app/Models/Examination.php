<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function competency_standard()
    {
        return $this->belongsTo(CompetencyStandard::class);
    }

    public function competency_element()
    {
        return $this->belongsTo(CompetencyElement::class, 'element_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
