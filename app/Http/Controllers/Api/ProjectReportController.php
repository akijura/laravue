<?php
/**
 * File RoleController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Laravue\Models\ProjectReport;
use App\Http\Resources\ProjectReportResource;
use App\Laravue\Models\Projects;
use Illuminate\Support\Arr;

/**
 * Class ProjectReportController
 *
 * @package App\Http\Controllers\Api
 */
class ProjectReportController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        $searchParams = $request->all();
        $projectQuery = ProjectReport::query();
        $projectId = Arr::get($searchParams, 'projectId', '');
     
            if(!empty($projectId))
            {
                $projectQuery->where('project_id', 'ILIKE', '%' . $projectId . '%');
                return ProjectReportResource::collection($projectQuery->get());
            }
            else {
                return "empty";
            }
    }
    public function statusConfirm($id)
    {
        if ($id === null) {
            return response()->json(['error' => 'Status not found'], 404);
        }  
        $currentUser = Auth::user();
        $current = $currentUser->roles[0]['name'];
        if($current === 'admin' || $current === 'moderator')
        {
            $confirm = ProjectReport::find($id);
            $confirm->status_confirm = 1;
            if($confirm->save())
            {
                $confirm_project = Projects::find($confirm->project_id);
                if($confirm_project != null)
                {
                    $confirm_project->status_confirm = 1;
                }
            }
        }
        else {
            return response()->json(['error' => 'You do not have rights for this function'], 404);
        }
      
        
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
}
