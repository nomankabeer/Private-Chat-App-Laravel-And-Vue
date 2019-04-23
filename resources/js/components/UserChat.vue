<template>
    <div class="chat private-chat-box" v-if="user_chat_id">
        <div class="chat-header clearfix">
            <!--<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar" />-->
            <img :src="other_user_avatar" alt="avatat"/>
            <div class="chat-about">
                <div class="chat-with">{{other_user_name}}</div>
                <div class="chat-num-messages"> <span v-if="chat.length > 0"> {{chat.length}} messages </span>  <!--<a> {{auth_user_id}} {{user_chat_id}}  </a>--></div>
            </div>
            <i class="fa fa-star"></i>
        </div> <!-- end chat-header -->
        <div class="chat-history scroll-style-2 scrol" id="scrol" v-on="pageScroll()">
            <ul class="" v-for="chat_msg, index in chat">


                <li class="clearfix" v-if="chat_msg.sender_id == auth_user_id" >
                    <div class="message-data align-right">
                        <span class="message-data-time" >{{chat_msg.created_at}}</span> &nbsp; &nbsp;
                        <span class="message-data-name"><i class="fa fa-circle online"></i> {{auth_user_name}}</span>
                    </div>
                    <div class="message my-message float-right">
                        {{chat_msg.message}}
                    </div>
                </li>

                <li class="clearfix" v-else >
                    <div class="message-data align-left">
                        <span class="message-data-name" >{{other_user_name}}</span> <i class="fa fa-circle me"></i>
                        <span class="message-data-time" >{{chat_msg.created_at}}</span> &nbsp; &nbsp;
                    </div>
                    <div class="message other-message ">
                        {{chat_msg.message}}
                    </div>
                </li>


            </ul>
        </div> <!-- end chat-history -->
        <div class="chat-message clearfix" >
            <input @keyup.enter="send()" v-model="message" class="text-box" placeholder ="Type your message" rows="3">
            <button @click="send()" class="text-box-button-style">Send</button>
        </div> <!-- end chat-message -->
    </div> <!-- end chat -->
</template>
<script>

    export default {

        props:[
            'user_chat_id',
            'auth_user_id',
        ],
        data(){
          return {
              chat:{},
              message:'',
              auth_user_name:'',
              other_user_avatar:'',
              other_user_name:'',
          }
        },
        methods:{
            send(){
                this.storeMessage();
                this.loadUserMessages(this.user_chat_id);
                this.message='';
            },
            storeMessage(){
                axios.post('/store/message', {
                    message: this.message,
                    receiver_id: this.user_chat_id,
                })
                    .then(response => {
                        // console.log(response.data);
                        // this.users = response.data;
                    })
                    .catch(error => {
                        // console.log(error);
                    });
            },
            loadUserMessages(id){
                // console.log('load user mesgs is loaded' , id);
                axios.post('/get/user/chat', {
                    receiver_id: id
                })
                    .then(response => {
                        this.chat = response.data;
                        // console.log(response.data , 'user chat against user id');

                    })
                    .catch(error => {
                        // console.log(error);
                    });
            },
            authUserDetails(){
                axios.post('/get/user/details', {
                    id: this.auth_user_id
                })
                    .then(response => {
                        let user_details = response.data;
                        this.auth_user_name = user_details.name;
                    })
                    .catch(error => {
                        // console.log(error);
                    });
            },
            otherUserDetails(){
                axios.post('/get/user/details', {
                    id: this.user_chat_id
                })
                    .then(response => {
                      let user_details = response.data;
                         this.other_user_name = user_details.name;
                         this.other_user_avatar = user_details.avatar;
                    })
                    .catch(error => {
                        // console.log(error);
                    });
            },
            pageScroll(){
                let container = document.querySelector('#scrol'); //this.$el.querySelector(".scrol");
                // if(container){container.scrollTop = container.scrollHeight;}
                setTimeout(function(){
                    container.scrollTop = container.scrollHeight;
                });
            },
        },
        watch:{
            user_chat_id(){
                // console.log('watch if user show msg id is changed' , this.user_chat_id);
                this.otherUserDetails();
                this.loadUserMessages(this.user_chat_id);

            }
        },
        mounted() {
            // console.log('Component mountedsssssssssssssssssssssss.' );
            this.authUserDetails();
        }
    }
</script>
