<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model3 extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'ref_target';
    use HasFactory;
}
