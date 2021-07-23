<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\StatusResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Status;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class StatusController extends BaseController
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
            $result = DB::select("select ts.*, bs.basic_status_name,bs.id as basic_id from types_status ts
            left join basic_status bs on bs.id=ts.basic_status
            where ts.main_status_id = ? order by ts.queue ASC",[$searchParams[0]]);
       

 
        $data = [];
        foreach ($result as $status) {
            $row = [
                'id' => $status->id,
                'name' => $status->name,
                'description' => $status->description,
                'queue' => $status->queue,
                'status' => $status->status,
                'basic_status_id' => $status->basic_id,
                'basic_status_name' => $status->basic_status_name,
                'main_status_id' => $status->main_status_id,
            ];
    
            $data[] = $row;
        }
        return response()->json(new JsonResponse( $data));
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
                $this->getValidationRules(),
                [
                    'statusName' => ['required', 'max:50'],
                    'mainStatusId' => ['required'],
                    'basicStatus' => ['required'],
                ]
            )
        );
       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $statuses = Status::where('main_status_id', 'LIKE', $params['mainStatusId'])->get();
            if($params['statusQueue'] === null)
            {
                
                if($statuses->isEmpty()== false)
                {
                    $validator = Validator::make(
                        $request->all(),
                        array_merge(
                        
                            [
                                'statusQueue' => ['required'],
                            ]
                        )
                    );
                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 403);
                    }
                }
                else {
                    
                    $params['statusQueue'] = 1; 
                }
            }
            else {
                $params['statusQueue'] = $params['statusQueue'] + 1;
            }
            $queueUpdate = DB::select("select ts.id,ts.queue from types_status ts
            where ts.main_status_id = ? and ts.queue >= ?",[$params['mainStatusId'],$params['statusQueue']]);
            if($queueUpdate != null)
            {
                foreach($queueUpdate as $queue)
                {
                    $queueFind = Status::find($queue->id);
                    $queueFind->queue = $queueFind->queue + 1;
                    $queueFind->update();
                }
            }
            $user = Status::create([
                'name' => $params['statusName'],
                'description' => $params['statusDescription'],
                'status' => $params['statusActive'],
                'queue' => $params['statusQueue'],
                'main_status_id' => $params['mainStatusId'],
                'basic_status' => $params['basicStatus'],
            ]);


            return "ok";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Status $status
     * @return StatusResource|\Illuminate\Http\JsonResponse
     */
    public function show(Status $status)
    {
        $result = DB::select("select ts.*, bs.basic_status_name,bs.id as basic_id from types_status ts
        left join basic_status bs on bs.id=ts.basic_status
        where ts.main_status_id = ? and ts.id != ? order by ts.queue ASC",[$status->main_status_id,$status->id]);
        $data = [];
        foreach ($result as $status) {
            $row = [
                'id' => $status->id,
                'name' => $status->name,
                'description' => $status->description,
                'queue' => $status->queue,
                'status' => $status->status,
                'basic_status_id' => $status->basic_id,
                'basic_status_name' => $status->basic_status_name,
                'main_status_id' => $status->main_status_id,
            ];
    
            $data[] = $row;
        }
        return response()->json(new JsonResponse(['current_statuses' => $data]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Status $status)
    {

        if ($status === null) {
            return response()->json(['error' => 'Status not found'], 404);
        }  
        $validator = Validator::make(
            $request->all(),
            array_merge(
                [
                    'editName' => ['required', 'max:50'],
                    'editMainStatusId' => ['required'], 
                    'editBasicStatus' => ['required'],
                ]
            )
        );
        $params = $request->all();
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $statuses = Status::all();
            if($params['editStatusQueue'] === null)
            {
                
                if($statuses->isEmpty() == false)
                {
                    $validator = Validator::make(
                        $request->all(),
                        array_merge(
                        
                            [
                                'editStatusQueue' => ['required'],
                            ]
                        )
                    );
                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->errors()], 403);
                    }
                }
                else {
                    
                    $params['editStatusQueue'] = 1; 
                }
            }
            else {
                $params['editStatusQueue'] = $params['editStatusQueue'] + 1;
            }
            $queueUpdate = DB::select("select ts.id,ts.queue from types_status ts
            where ts.main_status_id = ? and ts.queue >= ?",[$params['editMainStatusId'],$params['editStatusQueue']]);
            if($queueUpdate != null)
            {
                foreach($queueUpdate as $queue)
                {
                    $queueFind = Status::find($queue->id);
                    $queueFind->queue = $queueFind->queue + 1;
                    $queueFind->update();
                }
            }

            $status->name = $params['editName'];
            $status->description = $params['editDescription'];
            $status->status = $params['editActive'];
            $status->queue = $params['editStatusQueue'];
            $status->main_status_id = $params['editMainStatusId'];
            $status->basic_status = $params['editBasicStatus'];
            $status->save();
            return $status;
        }      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        try {
            $status->delete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules()
    {
        return [
            'statusName' => 'required|unique:types_status',
            'statusDescription' => 'required',
            'mainStatusId' => 'required',
        ];
    }
}
