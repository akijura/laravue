<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers\Api;

use App\Events\ProjectStatusChanged;
use App\Laravue\JsonResponse;

use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\ProjectComments;
use App\Laravue\Models\Files;
use App\Laravue\Models\ProjectReport;
use App\Laravue\Models\Projects;
use App\Laravue\Models\Status;

use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api
 */
class FilesController extends BaseController
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project_id = $request->get('id');
        $comment = $request->get('comment');
        $status_id = $request->get('status_id');
        if($project_id != null && $comment != null && $status_id != null)
        {
            $check_confirm_status = Projects::find($project_id);
            if($check_confirm_status->status_confirm === 1)
            {
                $currentUser = Auth::user();
                $current = $currentUser->roles[0]['name'];
                if($current === 'admin' || $current === 'moderator')
                {
                    $status_confirm = 1;
                }
                else {
                    $status_confirm = 0; 
                }
                $type_status = Status::find($status_id);
                $project = Projects::where('id', $project_id)->get()[0];
                $old_status = Status::find($project->type_status);
                $project->update(['type_status' => $status_id, 'basic_status' => $type_status->basic_status,'status_confirm' => $status_confirm]);
                
                $currentUser = Auth::user();
                $authorId = $currentUser['id'];
                $commentModel = ProjectComments::create([
                    'project_id' => $project_id,
                    'comment' => $comment,
                    'user_id' => $authorId,
                ]);
                $projectReport = ProjectReport::create([
                    'project_id' =>  $project_id,
                    'type_status' =>  $status_id,
                    'user_id' => $authorId,
                    'comment_id' => $commentModel->id,
                    'status_confirm' => $status_confirm
                ]);
                
                // Send notification
                ProjectStatusChanged::dispatch([
                    'author' => $currentUser['name'],
                    'project' => Projects::find($project_id),
                    'old_status' => $old_status,
                    'status' => $type_status,
                    'comment' => $commentModel->comment
                ]);
                    if($commentModel->id != null)
                    {
                     
                        if($request->hasFile('files')){
                            $pictures = [];
                            foreach($request->file('files') as $file)
                            {
                                $tempname = $file->getClientOriginalName();
                                $filename = date('m-d-Y H-i-s')." ".$tempname;
                                $filesModel = Files::create([
                                    'name' => $filename,
                                    'project_id' => $project_id,
                                    'comment_id' => $commentModel->id,
                                    'author' => $authorId,
                                ]);
                                $file->move(public_path('upload'),$filename);
                              
                            }     
                        }
                    }
                    return response()->json(new JsonResponse($commentModel));
                

    
            }
            else {
                return response()->json(['errors' => 'Last status must be confirmed by moderator or admin'], 403);
            }

        }

   
        
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
    public function downloadFile($file_name)
    {
        $file_path = public_path('upload/'.$file_name);
        return response()->download($file_path);
    //    return response()->file($file_path);
        
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
      
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        // try {
        //     $status->delete();
        // } catch (\Exception $ex) {
        //     return response()->json(['error' => $ex->getMessage()], 403);
        // }

        // return response()->json(null, 204);
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
