<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class RoleController extends Controller
{


    //all permission
    public function AllPermission() {
        $permissions = Permission::all();

        return view('backend.pages.permission.all_permission', compact('permissions'));
    }//end

    //add permission
    public function AddPermission() {
        return view('backend.pages.permission.add_permission');
    }//end

    //store permission in database
    public function StorePermission(Request $request) {
        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name
        ]);

       $notification = array(
            'message' => 'Permisson Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);
    }//end 

    //edit permission 
    public function EditPermission($id) {
        $permission = Permission::findOrFail($id);
        return view('backend.pages.permission.edit_permission', compact('permission'));
    }//end

    //update permission 
    public function UpdatePermission(Request $request) {
        $id = $request->id;

        Permission::findOrFail($id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name
        ]);

        $notification = array(
            'message' => 'Permisson Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);

    }//end

    //delete permission 
    public function DeletePermission($id) {
        Permission::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Permisson Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }//end


///////////////// all roles /////////////////

    public function AllRoles() {
        $roles = Role::all();

        return view('backend.pages.role.all_roles', compact('roles'));
    }


    //add role page setup
    public function AddRole() {
        return view('backend.pages.role.add_role');
    }

    //store role to database
    public function StoreRole(Request $request) {
        Role::create([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => 'Role Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);

    }

    //edit role
    public function EditRole($id) {
        $roles = Role::findOrFail($id);

        return view('backend.pages.role.edit_role', compact('roles'));
    }

    //update role
    public function UpdateRole(Request $request) {
        $id = $request->id;

        Role::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);
    }


    //delete role
    public function DeleteRole($id) {
        Role::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


/////////////// end all roles ///////////////



/////////////role in permission///////////////

    //role permission page
    public function RolesInPermission() {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_group = User::getPermissionGroup();
        return view('backend.pages.role.add_role_permission', compact('roles', 'permissions', 'permission_group'));
    }

    //store role permission
    public function StoreRolesPermission(Request $request) {
        $data = array();
        $permissions = $request->permission;

        foreach($permissions as $key => $permission){
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $permission;

            DB::table('role_has_permissions')->insert($data);
        }

        $notification = array(
            'message' => 'Role Permission Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.role.permission')->with($notification);

    }//end store role permission

    //all role permission
    public function AllRolePermission() {
        $roles = Role::all();
        return view('backend.pages.role.all_role_permission', compact('roles'));
    }//end all role permission
    

    //edit role permission
    public function EditRolePermission($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_group = User::getPermissionGroup();

        return view('backend.pages.role.edit_role_permission', compact('role', 'permissions', 'permission_group'));
    }//end edit role permission

    //update role permission
    public function UpdateRolesPermission(Request $request, $id) {
        $role = Role::findOrFail($id);
        $permissions = $request->permission;

        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }

        $notification = array(
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.role.permission')->with($notification);
    }//end update role permission

    //delete role permission
    public function DeleteRolesPermission($id) {
        $role = Role::findOrFail($id);

        if(!is_null( $role)){
            $role->delete();
        }
        $notification = array(
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }



///////////end role in permission/////////////


}
