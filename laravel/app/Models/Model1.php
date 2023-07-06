<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model1 extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'master_ticket';
    use HasFactory;
}
