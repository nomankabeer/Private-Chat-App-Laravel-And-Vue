@extends('layouts.chat_app')
@section('content')
    <div class="container clearfix mybody" id="chat_app">
    <input type="hidden" name="auth_user_id"  value="{{Auth::user()->id}}">
    <input type="hidden" name="asset_storage_path"  value="{{asset('storage/')}}">
            <user-list
                :auth_user_id="auth_user_id"
                :asset_path="asset_path"
            >
            </user-list>
    </div>
@endsection
