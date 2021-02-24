<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Truth extends Model
{
    use UsesUuid;
    protected $fillable = ['truth', 'level', 'user_id'];
    protected $table = 'truths';
    protected $primaryKey = "uuid";
    protected $guarded = ['uuid'];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "uuid");
    }
}
