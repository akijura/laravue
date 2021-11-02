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
use App\Http\Resources\ProjectResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Status;
use App\Laravue\Models\Projects;
use App\Laravue\Models\ProjectUsers;
use App\Laravue\Models\ProjectComments;
use App\Laravue\Models\ProjectReport;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\ProjectLevel;
use Validator;
use App\Laravue\Models\Comments;
use App\Http\Resources\CommentsResource;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class CommentsController extends BaseController
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
        $commentQuery = Comments::query();
        $id = Arr::get($searchParams, 'id', '');
    
            if(!empty($id))
            {
                $commentQuery->where('project_id', 'ILIKE', '%' . $id . '%');
                return CommentsResource::collection($commentQuery->get());
            }
            else {
                return new CommentsResource();
            }

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
                    'projectName' => ['required'],
                    'projectDescription' => ['required'],
                    'projectBeginDate' => ['required'],
                    'projectEndDate' => ['required'],
                    'projectStatusActive' => ['required'],
                    'projectTypeStatus' => ['required'],
                    'projectExecutors' => ['required'],
                ]
            )
        );
   
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
           
            $currentUser = Auth::user();
            $authorId = $currentUser['id'];
            $params = $request->all();
            $date = date('Y-m-d', strtotime($params['projectEndDate']));
            $status = Status::where('main_status_id', 'LIKE', $params['projectTypeStatus'])->orderBy('queue', 'asc')->get(); // get first status by queue
            $status_id = $status[0]['id'];
            $basic_status = $status[0]['basic_status'];
       
            $project = Projects::create([
                'name' => $params['projectName'],
                'description' => $params['projectDescription'],
                'begin_date' => date('Y-m-d', strtotime($params['projectBeginDate'])),
                'end_date' => date('Y-m-d', strtotime($params['projectEndDate'])),
                'main_status_id' => $params['projectTypeStatus'],
                'type_status' =>  $status_id,
                'basic_status' =>  $basic_status,
                'project_level' =>  $params['projectLevel'],
                'author_id' => $authorId,
                'status' => $params['projectStatusActive'],
            ]);

            $projects = Projects::where('name', 'LIKE', $params['projectName'])->get();
            foreach ($params['projectExecutors'] as $executor)
            {
                $projectUsers = ProjectUsers::create([
                    'project_id' => $projects[0]['id'],
                    'user_id' => $executor,
                ]);
            }
            if($params['projectComment'] != null)
            {
                $comment = ProjectComments::create([
                    'project_id' => $projects[0]['id'],
                    'comment' => $params['projectComment'],
                    'user_id' => $authorId,
                ]);
            }
            $projectReport = ProjectReport::create([
                'project_id' => $projects[0]['id'],
                'type_status' =>  $status_id,
                'user_id' => $authorId,
                'comment_id' => $comment->id,
            ]);

            return $projectReport;
        }
    }
    public function projectLevelList()
    {

        $projectLists = ProjectLevel::all();
        return response()->json(new JsonResponse($projectLists));
    }
    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return new UserResource($user);
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
                    'editStatusQueue' => ['required'],
                ]
            )
        );
   
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $status->name = $params['editName'];
            $status->description = $params['editDescription'];
            $status->status = $params['editActive'];
            $status->queue = $params['editStatusQueue'];
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
