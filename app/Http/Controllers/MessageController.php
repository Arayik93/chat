<?php


namespace App\Http\Controllers;


use App\Events\NewRoomEvent;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
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

        public function newRoom(Request  $request){
            if($request->has("user_id") ){
                $user = User::find($request->get("user_id"));
                $room = new Room();
                $room->save();
                $user->rooms()->sync([$room->id],false);
                Auth::user()->rooms()->sync([$room->id],false);
                broadcast(new NewRoomEvent($user));
                return response(['room' => $room,'user' => $user,]);
            }
        }
}
