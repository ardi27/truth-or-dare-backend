<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Truth extends Model
{
    use UsesUuid;
    protected $fillable = ['truth', 'level'];
    protected $table = 'truths';
    protected $primaryKey = "uuid";
    protected $guarded = ['uuid'];
}
