<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Resources\Organization as OrganizationResource;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new OrganizationResource(Organization::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Organization::firstOrCreate($request->json()->all());

        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        return new OrganizationResource($organization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function trelloOrganization(Organization $organization)
    {
        $headers = array(
            'Accept' => 'application/json'
        );

        $query = array(
            'key' => env('TRELLO_API_KEY'),
            'token' => env('TRELLO_TOKEN')
        );

        $response = \Unirest\Request::get(
            'https://api.trello.com/1/members/me/organizations',
            $headers,
            $query
        );

        $data = array();
        foreach($response->body as $value) {
            $data[] = [
                'idModel' => $value->id,
                'name' => $value->name,
                'displayName' => $value->displayName,
                'teamType' => $value->teamType,
                'desc' => $value->desc,
                'url' => $value->url,
                'boards' => count($value->idBoards),
                'memberships' => count($value->memberships)
            ];
        }

        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }
}
