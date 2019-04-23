@extends('layouts.chat_app')
@section('content')
    <div class="container clearfix mybody" style="width: 780px !important" id="chat_app">
    <input type="hidden" name="auth_user_id"  value="{{Auth::user()->id}}">
        {{--@{{ auth_user_id }}--}}
            <user-list
                :auth_user_id="auth_user_id"
            ></user-list>


     {{--   <user-chat></user-chat>--}}






{{--

        <chat v-for="chat_msg,index in chat.message"
              :key="chat_msg.index"
              :user="user"
              :mymsg="chat.myMsg[index]"
              :time="chat.time[index]"
              :message="chat_msg"
        >@{{ chat_msg }}</chat>
--}}

        {{--<li class="clearfix">
        <div class="message-data align-left">
            <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>
            <span class="message-data-time" >10:10 AM, Today</span> &nbsp; &nbsp;
        </div>
        <div class="message other-message ">
            Hi Vincent, how are you? How is the project coming along?
        </div>
    </li>--}}

    </div> <!-- end container -->

@endsection