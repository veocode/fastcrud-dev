<?php

namespace Veocode\FastCRUD\Models;

use App\Fields\FieldEmail;
use App\Fields\FieldFlag;
use App\Fields\FieldInt;
use App\Fields\FieldPassword;
use App\Fields\FieldString;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class CrudUser extends CrudModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Notifiable, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        return (bool)($this->is_admin ?? false);
    }

    public static function getFields() : array {
        return [
            FieldInt::make('id', 'ID')
                ->hideInForm()
                ->virtual(),

            FieldEmail::make('email', 'E-mail')
                ->setFormTitle('E-mail пользователя')
                ->validate('required|email|unique:users')
                ->validateOnUpdate('required|email'),

            FieldString::make('name', 'Имя пользователя')
                ->validate('required'),

            FieldPassword::make('password1', 'Пароль')
                ->virtual()
                ->validate('required|min:8|max:16')
                ->validateOnUpdate('nullable|min:8|max:16'),

            FieldPassword::make('password2', 'Повторите пароль')
                ->virtual()
                ->validate('required_with:password1|same:password1'),

            FieldFlag::make('is_admin', 'Администратор'),
        ];
    }

}
