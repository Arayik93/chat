<?php


namespace App\Http\Controllers;


use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
        public function sendMessage(Request  $request){

            if($request->has("room_id") && $request->has("body")){
                $message = new Message();
                $message->user_id = Auth::id();
                $message->room_id = $request->get("room_id");
                $message->body = $request->get("body");
                $message->save();
            }
            return response()->json($request->all());
        }
}
