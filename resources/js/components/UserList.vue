<template>
<span >
    <div class="people-list" id="people-list">
    <ul class="over-flow-y-scroll list scroll-style-1">
        <li class="clearfix" v-for="user,index in users">
            <a href="#" v-on:click="show_chat_id =  user.id">
            <img :src="asset_path+'/'+user.avatar" class="img_process" alt="avater"/>
                </a>
            <div class="about">
                <div class="name">{{ user.name}}</div>
                <div class="status">
                   <span v-if="user.new_message != 0" :style="{display: user_msg_notification}" :id="user.id" class=" old badge-danger badge"> {{user.new_message}}  new </span>
                   <span  :id="'new_'+user.id" class="badge-danger badge">  </span>
                </div>
            </div>
        </li>
    </ul>

    </div>
    <user-chat
    :user_chat_id="show_chat_id"
    :auth_user_id="auth_user_id"
    :asset_path="asset_path"
    v-on:is_new_message="new_message_there"
    v-on:zero_msg_notification="remove_notification"
    ></user-chat>
    </span>
</template>
<script>
    import Userchat from './UserChat';
    export default {
        data(){
            return{
            users: {},
                show_chat_id:'',
                user_msg_notification: 'block',
            }
        },
        props:['auth_user_id' , 'asset_path'],
        methods: {
            getUserList(){
                axios.get('/get/user/list', {
                })
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(error => {
                    });
            },
            new_message_there: function(value){
                this.user_msg_notification = 'none';
                $('#new_'+value.sender_id).css('display' , 'block').html(value.total_unread_messages + ' new');
            },
            remove_notification: function(value){
                setTimeout(function() {
                    $('#new_' + value).css('display', 'none');
                    this.user_msg_notification = 'none';
                },1000);
            }
        },
        components: {
            Userchat
        },
        mounted() {
            this.getUserList();
        },
    }
</script>
