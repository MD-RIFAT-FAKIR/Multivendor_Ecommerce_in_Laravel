<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>s
     */
    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //retun cache created in Role moddleware
    public function UserActiveStatus() {
        return Cache::has('user-is-active' . $this->id);
    }

    //get permission group
    public static function getPermissionGroup() {
        $permission_group = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_group;
    }//end

    //get permission by gorup_name
    public static function getPermissionByGroupName($group_name) {
        $permissions = DB::table('permissions')->select('name', 'id')->where('group_name', $group_name)->get();

        return  $permissions;
    }//end

    //check role has permissions
    public static function roleHasPermission($role,$permissions) {
        $hasPermission = true;
        foreach($permissions as $permission) {
            if(!$role->hasPermissionTo($permission->name)){
                return $hasPermission = false;
            }
           return $hasPermission; 
        }
        
    }//end check role has permissions

}
