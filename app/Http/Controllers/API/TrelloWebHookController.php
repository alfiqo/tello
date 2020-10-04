<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\TrelloWebHook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\TrelloWebHook as TrelloWebHookResource;
use Telegram\Bot\Laravel\Facades\Telegram;

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

        $action = $request->input('action');
        $model = $request->input('model');

        $message = '';
        $creator = $action['memberCreator']['username'];
        if ($action['type'] === 'updateCard') {
            $card = $action['display']['entities']['card']['text'];
            $listBefore = $action['display']['entities']['listBefore']['text'];
            $listAfter = $action['display']['entities']['listAfter']['text'];
            $message = "{$creator} move a {$card} from {$listBefore} to {$listAfter} on <a href='{$model['shortUrl']}'> {$model['name']}</a>";
        }
        if ($action['type'] === 'addMemberToBoard') {
            $member = $action['display']['entities']['member']['username'];
            $message = "{$creator} add {$member} to <a href='{$model['shortUrl']}'> {$model['name']}</a>";
        }
        if ($action['type'] === 'createCard') {
            $card = $action['display']['entities']['card']['text'];
            $list = $action['display']['entities']['list']['text'];
            $message = "{$creator} create a card {$card} in list of {$list} on <a href='{$model['shortUrl']}'> {$model['name']}</a>";
        }
        if ($action['type'] === 'addMemberToCard') {
            $card = $action['display']['entities']['card']['text'];
            $message = "{$creator} join a {$card} on <a href='{$model['shortUrl']}'> {$model['name']}</a>";
        }

        Telegram::sendMessage([
            'chat_id' => '-421918409',
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);

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
            'description' => $request->get('name'),
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
