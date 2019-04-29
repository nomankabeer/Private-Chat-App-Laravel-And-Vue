<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Chat;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DB;
class ChatController extends Controller
{
    public function getUserList(){
//        return [User::where('id' , '<>' , Auth::user()->id)->get() , Auth::user()->id];
        return User::where('id' , '<>' , Auth::user()->id)->get();
    }
    public function getUserChat(Request $request){
        $receiver_id = $request->receiver_id;
        $Messages = Chat::
        where(function($q) use($receiver_id){ $q->where('sender_id' , Auth::user()->id); $q->where('receiver_id' , $receiver_id); })
            ->orWhere(function($q) use($receiver_id){ $q->where('receiver_id' , Auth::user()->id); $q->Where('sender_id' , $receiver_id); })
//            ->limit(1)->page(1)->get();
            ->orderBy('id' , 'DESC')->paginate(20);
//        $collection = collect($Messages);// Chat::get();
//return $collection->sortKeysDesc();
        return $Messages;


    }

    public function getUserChat3(Request $request){
        $receiver_id = 2;
        $Messages = Chat::
        where(function($q) use($receiver_id){ $q->where('sender_id' , Auth::user()->id); $q->where('receiver_id' , $receiver_id); })
            ->orWhere(function($q) use($receiver_id){ $q->where('receiver_id' , Auth::user()->id); $q->Where('sender_id' , $receiver_id); })

//            ->orderBy('id' , 'ASC')->paginate(20);
       // return $Messages;// Chat::get();

        ->latest()->paginate(20);
        $collection = collect($Messages);// Chat::get();
        return  $collection->sortKeysDesc();
//        return  $collection;

    }
    public function storeMessage(Request $request){
        event(new ChatEvent($request));
//        $receiver_id = $request->receiver->id;
        $request = $request->all();
        unset($request['_token']);
        $request['sender_id'] = Auth::user()->id;
        Chat::insert($request);
        $receiver_id = $request['receiver_id'];
        Chat::Where(function($q) use($receiver_id){ $q->where('receiver_id' , Auth::user()->id); $q->Where('sender_id' , $receiver_id); })->update(['is_seen' => 1]);
        return Chat::Where(function($q) use($receiver_id){ $q->where('sender_id' , Auth::user()->id); $q->where('receiver_id' , $receiver_id); })->count();
        return 'success';
    }
    public function getUserAvatar(Request $request){
        return User::find($request->id)->avatar;
    }
    public function userDetails(Request $request){
        return User::find($request->id);
    }
    public function deleteMessage(Request $request){
        return Chat::where('id' , $request->id)->delete();
    }

}
