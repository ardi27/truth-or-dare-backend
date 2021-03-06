<?php

namespace App\Models;

use App\Http\Traits\UsesUuid;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'profile_photo_url', 'name', 'password'
    ];
    protected $table = "users";
    protected $primaryKey = "uuid";
    protected $guarded = ['uuid'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    public function truth()
    {
        return $this->hasMany(Truth::class, "user_id", "uuid");
    }
    public function dare()
    {
        return $this->hasMany(Dare::class, "user_id", "uuid");
    }
}
