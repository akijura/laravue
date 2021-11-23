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
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class ProjectController extends BaseController
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
        $currentUser = Auth::user();
        $current = $currentUser->roles[0]['name'];
        $searchParams = $request->all();
        $projectQuery = Projects::query();
        $keyword = Arr::get($searchParams, 'keyword', '');
        $id = Arr::get($searchParams, 'id', '');
        $projectDetail = Arr::get($searchParams, 'projectDetail', '');
        if ( $current === 'admin'){
            if($projectDetail == 'OK')
            {
                if(!empty($id))
                {
                    $projectQuery->where('id', 'ILIKE', '%' . $id . '%');
                    return ProjectResource::collection($projectQuery->get());
                }
                else {
                    return "empty";
                }
            }
            else {
                if (!empty($keyword)) {
                    $projectQuery->where('name', 'ILIKE', '%' . $keyword . '%');
                    $projectQuery->orWhere('description', 'ILIKE', '%' . $keyword . '%');
                }
                return ProjectResource::collection($projectQuery->get());
            }
        }
        else 
        {
            $temp = [];
            $temp2 = [];
            $projects_id = DB::select("select distinct project_id from project_users
            where user_id = ? ",[$currentUser['id']]);
            foreach ($projects_id as $project) {
                array_push($temp,$project->project_id);
            }
            $projects_id2 = DB::select("select id from projects
            where author_id = ? ",[$currentUser['id']]);
            foreach ($projects_id2 as $project) {
                array_push($temp,$project->id);
            }
            $temp2 = array_unique($temp);
           
            if($projectDetail == 'OK')
            {
                if(!empty($id))
                {
                    $projectQuery->where('id', 'ILIKE', '%' . $id . '%');
                    //$projectQuery->whereIn('id', $temp2);
                    return ProjectResource::collection($projectQuery->get());
                }
                else {
                    return "empty";
                }
            }
            else {
                if (!empty($keyword)) {
                    $projectQuery->where('name', 'ILIKE', '%' . $keyword . '%')->whereIn('id', $temp2);
                    // $projectQuery->where('name', 'ILIKE', '%' . $keyword . '%');
                    // $projectQuery->orWhere('description', 'ILIKE', '%' . $keyword . '%');  
                }
                else {
                    $projectQuery->whereIn('id', $temp2);
                }
                return ProjectResource::collection($projectQuery->get());
            }

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
                    'projectComment' => ['required'],
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
                'status_confirm' => 1,
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
                'status_confirm' => 1,
            ]);

            return  $comment;
        }
    }
    public function projectLevelList()
    {

        $projectLists = ProjectLevel::all();
        return response()->json(new JsonResponse($projectLists));
    }
    public function getProjectAuth($project_id)
    {
        $project = Projects::find($project_id)->first();
        $user = User::find($project->author_id);
        return response()->json(new JsonResponse(['author_name' => $user]));
    }
    public function getProjectMembers($project_id)
    {
        $users = DB::select("select  us.name,us.id,us.email from project_users pu
        left join users us on us.id=pu.user_id
        where pu.project_id = ? ",[$project_id]);
        $data = [];
        foreach ($users as $user) {
            $row = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
    
            $data[] = $row;
        }
        return response()->json(new JsonResponse( $data));
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
    public function addProjectMember(Request $request)
    {
     
        $params = $request->all();
        if($params['projectId'] != null)
        {
            $projectId = $params['projectId'];
            DB::table('project_users')
            ->where([
                ['project_id', $projectId]])
            ->delete(); 

            $members = $params['projectExecutors'];
            if($members != null)
            {
                foreach($members as $member )
                {
                    ProjectUsers::create([
                        'project_id' =>  $projectId,
                        'user_id' => $member,
                    ]);
                }
            }
        }
        return response()->json(new JsonResponse(['params' => $params]));  
    }

    public function confirmStatus($project_id,$id)
    {
        $currentUser = Auth::user();
        $current = $currentUser->roles[0]['name'];
        if($current === 'admin' || $current === 'moderator')
        {
            $project = Projects::find($project_id);
            $project->status_confirm = 1;
            $project->update();
            $projectReport = ProjectReport::find($id);
            $projectReport->status_confirm = 1;
            $projectReport->update();
            return $id;
        }
        else {
            return response()->json(['error' => 'Permission denied'], 403);
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
