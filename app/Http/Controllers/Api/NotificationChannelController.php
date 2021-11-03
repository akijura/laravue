<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationChannelResource;
use App\Laravue\Models\NotificationChannel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  NotificationChannelResource::collection(NotificationChannel::all());        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(['name', 'api_link']), [
            'name' => 'required',
            'api_link' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if($validator->validated()) {
            NotificationChannel::create([
                'name' => $validator->validated()['name'],
                'api_link' => $validator->validated()['api_link'],
            ]);

            return response()->json(['message' => 'Notification channel successfully created!'], 200);
        }
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
