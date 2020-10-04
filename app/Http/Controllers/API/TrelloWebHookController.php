<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\TrelloWebHook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\TrelloWebHook as TrelloWebHookResource;

class TrelloWebHookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new TrelloWebHookResource(TrelloWebHook::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        TrelloWebHook::create($request->json()->all());

        return response()->json([
            'message' => 'success',
            'status' => Response::HTTP_OK
        ]);
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initWebHook(Request $request)
    {
        $headers = array(
            'Accept' => 'application/json'
        );

        $callbackUrl = 'https://fiqo.arsiteknologi.com/api/trello-webhook';

        $query = array(
            'key' => env('TRELLO_API_KEY'),
            'token' => env('TRELLO_TOKEN'),
            'description'=> $request->get('name'),
            'callbackURL' => $callbackUrl,
            'idModel' => $request->get('idModel')
        );

        $response = \Unirest\Request::post(
            'https://api.trello.com/1/webhooks/',
            $headers,
            $query
        );

        return response()->json([
            'data' => $response
        ], Response::HTTP_OK);
    }

}
