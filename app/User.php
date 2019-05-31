<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'name', 'email', 'password', 'surname', 'patronymic',
        'login', 'id_institution', 'img', 'status', 'subgroup',
        'group'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public static function getSubgroup(int $id)
    {
      $user = User::find($id);
      return $user->subgroup;
    }
    public static function getIdGroup(int $id)
    {
      $user = User::find($id);
      return $user->id_group;
    }
}
