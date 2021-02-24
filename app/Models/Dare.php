<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Dare extends Model
{
    //
    use UsesUuid;
    protected $fillable = ['dare', 'level', 'user_id'];
    protected $table = 'dares';
    protected $primaryKey = "uuid";
    protected $guarded = ['uuid'];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "uuid");
    }
}
