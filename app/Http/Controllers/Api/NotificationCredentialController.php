<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Laravue\Models\NotificationCredential;
use App\Http\Resources\NotificationCredentialResource;
use App\Http\Resources\UserResource;
use App\Laravue\Models\NotificationChannel;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationCredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select('id','name')
                       ->with('notificationCredentials', 'notificationCredentials.channel')
                       ->get();

      return response()->json(['data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(['channel_id','user_id','identifier']), [
            'user_id' => ['required', 'numeric'],
            'channel_id' => ['required', 'numeric'],
            'identifier' => ['required', 'string'],
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }

        $validated = $validator->validated();

        NotificationCredential::create([            
            'user_id' => $validated['user_id'],
            'channel_id' => $validated['channel_id'],
            'identifier' => $validated['identifier'],
        ]);

        return response()->json(['message' => 'Notification credential successfully created!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
