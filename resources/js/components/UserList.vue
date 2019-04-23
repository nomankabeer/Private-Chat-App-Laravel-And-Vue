<template>
<span>
    <div class="people-list" id="people-list">
        <div class="search">
            <input type="text" placeholder="search" />
            <i class="fa fa-search"></i>
        </div>
    <ul class="over-flow-y-scroll scroll-style-1">


        <li class="clearfix" v-for="user,index in users">
            <a href="#" v-on:click="show_chat_id =  user.id">
            <img :src="user.avatar" alt="avater"/>
                </a>
            <div class="about">
                <div class="name">{{ user.name}}</div>
                <div class="status">
                    <i class="fa fa-circle online"></i> online
                    <!--<a :href="'?id='+user.id">send msg</a> offline -->
                </div>
            </div>
        </li>
    </ul>
    </div>
    <user-chat
    :user_chat_id="show_chat_id"
    :auth_user_id="auth_user_id"
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
            }
        },
        props:['auth_user_id'],
        methods: {

            getUserList(){
                axios.get('/get/user/list', {
                })
                    .then(response => {
                        this.users = response.data;
                        // console.log(response.data , 'user list');

                    })
                    .catch(error => {
                        // console.log(error);
                    });
            },

        },
        components: {
            Userchat
        },
        mounted() {
            // console.log('Component mounted.');
            this.getUserList();
        }
    }
</script>
