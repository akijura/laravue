<?php
/**
 * File RoleController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Laravue\Models\Permission;
use Illuminate\Http\Request;
use App\Laravue\Models\Role;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * Class RoleController
 *
 * @package App\Http\Controllers\Api
 */
class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array_merge(
                [
                    'role' => ['required', 'max:50'],
                ]
            )
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $role = Role::create([
                'name' => $params['role'],
            ]);
            return new RoleResource($role);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  Role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if ($role === null || $role->isAdmin()) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $permissionIds = $request->get('permissions', []);
        $permissions = Permission::allowed()->whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
        $role->save();
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Role $role)
    {
        $check_role = DB::select("select  * from role_has_permissions where role_id = ? ",[$role->id]);
        if($check_role == null)
        {
            try {
                $role->delete();
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        }
        else 
        {
            return response()->json(['error' => "Can not delete this role,it has been used for permissions"], 403);
        }
    }

    /**
     * Get permissions from role
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function permissions(Role $role)
    {
        return PermissionResource::collection($role->permissions);
    }
}
