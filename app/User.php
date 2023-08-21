<?php

namespace App;

use App\Models\AssignToolKit;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','first_name','middle_name','last_name', 'email','emp_code','emp_designation','gender','dob','pan_card_no','password','user_type','role','ph_no','state','district','pin_code','address','remarks'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $rules = [
        'title'                         => 'required',
        'first_name'                   =>  'required|min:3',
        // 'middle_name'                  =>  'required|min:3',
        // 'last_name'                    =>  'required|min:3',
        // 'email'                        =>  'required|email|unique:users',
        'emp_designation'              =>  'required',
        'emp_code'                      => 'required|unique:users',
        // 'dob'                          =>  'required',
        // 'pan_card_no'                  =>  'required',
        // 'password'                     =>  'required|min:6',
        // 'password_confirmation'        =>  'required|min:6|same:password',
        'role'                         =>  'required',
        // 'ph_no'                        =>  'required',
        // 'gender'                        =>  'required',
        // 'state'                        =>  'required',
        // 'district'                     =>  'required',
        // 'pin_code'                     =>  'required',
        // 'address'                      =>  'required',
        // 'remarks'                      =>  'required',
    ];

    public function role()
    {
        return $this->hasOne('App\Models\Role');
    }

    public function assigned_engg() {
        return $this->hasMany("App\Models\Assign\AssignEngineer", "engineer_id")->where("status", 1);
    }

    public function full_name()
    {
        return $this->first_name.($this->middle_name ? " ".$this->middle_name." " : " ").$this->last_name;
    }

    public function assigned_toolkits( )
    {
        return $this->hasMany(AssignToolKit::class, "user_id", "id")->where("status", 1);
    }
}
