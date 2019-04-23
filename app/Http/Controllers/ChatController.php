<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use DB;
class ChatController extends Controller
{
    public function getUserList(){
        return User::where('id' , '<>' , Auth::user()->id)->get();
    }
    public function getUserChat(Request $request){
//         $chat = chat::where('sender_id' , Auth::user()->id , ['message' => true])->get();
        $receiver_id = $request->receiver_id;
        $Messages = Chat::
        where(function($q) use($receiver_id){ $q->where('sender_id' , Auth::user()->id); $q->where('receiver_id' , $receiver_id); })
            ->orWhere(function($q) use($receiver_id){ $q->where('receiver_id' , Auth::user()->id); $q->Where('sender_id' , $receiver_id); })
            ->get();
          return $Messages;// Chat::get();

    }
    public function storeMessage(Request $request){
        $request = $request->all();
        unset($request['_token']);
        $request['created_at'] = Carbon::now();
        $request['sender_id'] = Auth::user()->id;
        Chat::insert($request);
        return 'success';
    }
    public function getUserAvatar(Request $request){
        return User::find($request->id)->avatar;
    }
    public function userDetails(Request $request){
        return User::find($request->id);
    }
}
