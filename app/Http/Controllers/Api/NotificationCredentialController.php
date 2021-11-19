<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Laravue\Models\NotificationCredential;
use App\Http\Resources\NotificationCredentialResource;
use App\Http\Resources\UserResource;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationCredentialController extends Controller
{

    public function index()
    {
        $users = User::select('id', 'name')
            ->with('notificationCredentials', 'notificationCredentials.channel')
            ->get();

        return response()->json(['data' => $users]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(['channel_id', 'user_id', 'identifier']), [
            'user_id' => ['required', 'numeric'],
            'channel_id' => ['required', 'numeric'],
            'identifier' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
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


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(['user_id', 'channel_id','identifier']), [
            'user_id' => 'required|numeric',
            'channel_id' => 'required|numeric',
            'identifier' => 'required',
        ]);

        $credential = null;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $credential = NotificationCredential::find($id);

        if ($credential === null) {
            return response()->json(
                [
                    "success" => false,
                    "data" => ["error" => "Credential not found"]
                ],
                404
            );
        }

        if ($validator->validated() && $credential !== null) {
            $credential->user_id = $validator->validated()['user_id'];
            $credential->channel_id = $validator->validated()['channel_id'];
            $credential->identifier = $validator->validated()['identifier'];
            $credential->save();

            return response()->json(['message' => 'Notification channel successfully updated!'], 200);
        }
    }


    public function destroy($id)
    {
        $channel = NotificationCredential::find($id);

        if ($channel === null) {
            return response()->json(
                [
                    "success" => false,
                    "data" => ["error" => "Notification credential not found"]
                ],
                404
            );
        }

        if ($channel->delete()) {
            return response()
                ->json(['message' => 'Notification credential successfully deleted!'], 200);
        }

        return  response()
            ->json(['message' => 'Oops, unexpected error'], 400);
    }
}
