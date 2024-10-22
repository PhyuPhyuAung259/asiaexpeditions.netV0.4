<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function bank(){
        return $this->hasMany(AccountTransaction::class);
    }

    public function project()
    {
        return $this->belongsToMany(Project::class);
    }

    public function photo (){
        return $this->hasMany(\App\Photo::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public static function Exitemail($email){
        // return self->select('email')->where('email', $email)->first();
        return self::where('email', $email)->first();
    }

    public function doc(){ 
        return $this->hasMany(DocsModel::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function supplier() {
        return $this->hasMany(Supplier::class);
    }

    public function country (){
        return $this->belongsTo(Country::class);
    }

    public function province (){
        return $this->belongsTo(Province::class);
    }
}
