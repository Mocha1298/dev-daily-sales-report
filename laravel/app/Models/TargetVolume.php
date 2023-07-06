<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetVolume extends Model
{
    use HasFactory;
    protected $connection = 'db_target';
    protected $table = 'new_master_target_volume';
    public $timestamps = false;
}
