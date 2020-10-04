<?php

namespace App\Http\Controllers\API;

use App\Board;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Board as BoardResource;
use Illuminate\Http\Response;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new BoardResource(Board::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Board::firstOrCreate($request->json()->all());

        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        //
    }

    /**
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function trelloBoard(string $organization)
    {
        $headers = array(
            'Accept' => 'application/json'
        );

        $query = array(
            'key' => env('TRELLO_API_KEY'),
            'token' => env('TRELLO_TOKEN')
        );

        $response = \Unirest\Request::get(
            "https://api.trello.com/1/organizations/{$organization}/boards",
            $headers,
            $query
        );

        $data = array();

        foreach ($response->body as $value) {
            $data[] = [
                'idModel' => $value->id,
                'idOrganization' => $value->idOrganization,
                'name' => $value->name,
                'desc' => $value->desc,
                'url' => $value->url
            ];
        }

        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }
}
