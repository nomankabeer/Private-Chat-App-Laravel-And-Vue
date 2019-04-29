<template>
    <div class="chat private-chat-box" v-if="user_chat_id">
        <div class="chat-header clearfix">
            <img :src="asset_path+'/'+other_user_avatar" class="img_process" alt="avatat"/>
            <div class="chat-about">
                <div class="chat-with">{{other_user_name}}</div>
                <div class="chat-num-messages"> <span > {{total_messages}} messages </span> </div>
            </div>
            <i class="fa fa-star"></i>
        </div> <!-- end chat-header -->
        <div class="chat-history scroll-style-2 scrol" id="scrol">
            <ul class="" v-for="chat_msg, index in chat">
                <li class="clearfix" v-if="chat_msg.sender_id == auth_user_id" :id="'delete_message_'+chat_msg.id">
                    <div class="message-data align-right"> <a  v-on:click="deleteMessage(chat_msg.id)" class="badge badge-danger text-white">Delete</a> &nbsp;<span v-if="chat_msg.is_seen == 1" class="badge badge-info text-white">seen</span>
                        <span class="message-data-time" >{{chat_msg.date}}</span> &nbsp; &nbsp;
                        <span class="message-data-name"><i class="fa fa-circle online"></i> {{auth_user_name}}</span>
                    </div>
                    <div class="message my-message float-right">
                        {{chat_msg.message}} {{chat_msg.id}}
                    </div>
                </li>
                <li class="clearfix" v-else >
                    <div class="message-data align-left">
                        <span class="message-data-name" >{{other_user_name}}</span> <i class="fa fa-circle me"></i>
                        <span class="message-data-time" >{{chat_msg.date}}</span>
                    </div>
                    <div class="message other-message ">
                        {{chat_msg.message}} {{chat_msg.id}}
                    </div>
                </li>
            </ul>
            <button v-if="next_page_url != null " class=" btn btn-primary load_previous_msg" @click="loadUserMessagesPaginate(user_chat_id)" > Load Previous Messages </button>
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
            'asset_path'
        ],
        data(){
          return {
              chat:{},
              message:'',
              auth_user_name:'',
              other_user_avatar:'',
              other_user_name:'',
              next_page_url:'',
              last_page_url:'',
              check_scroll: true,
              total_messages: '',
          }
        },
        methods:{
            deleteMessage(id){
                let container = document.querySelector('#delete_message_'+id);
                container.style.display = 'none';
                this.total_messages -= 1;
                axios.post('/delete/message', {
                    id: id
                });
            },
            currentDateTime(){
                let date = new Date();
                let hours = date.getHours();
                let minutes = date.getMinutes();
                let seconds = date.getSeconds();
                let ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                let Time = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                return date.toDateString() + ' | ' + Time;
            },
            send(){
                if(this.message != '' && this.message != ' '){
                this.$emit('zero_msg_notification' , this.user_chat_id);
                this.check_scroll = true;
                this.total_messages++;
                this.chat.unshift({message: this.message , receiver_id: this.user_chat_id , sender_id: this.auth_user_id , date: this.currentDateTime() });
                this.storeMessage(this.message);
                this.message='';
                }
            },
            storeMessage(message){
                axios.post('/store/message', {
                    message: message,//this.message,
                    receiver_id: this.user_chat_id,
                    date: this.currentDateTime()
                })
            },
            loadUserMessages(id){
                axios.post('get/user/chat', {
                    receiver_id: id
                })
                    .then(response => {
                        this.chat = response.data.data;
                        this.next_page_url = response.data.next_page_url;
                        this.last_page_url = response.data.last_page_url;
                        this.total_messages = response.data.total;

                    })
                    .catch(error => {
                    });
            },
            loadUserMessagesPaginate(id){
                axios.post(this.next_page_url, {
                    receiver_id: this.user_chat_id
                })
                    .then(response => {
                        this.check_scroll = false;
                        let chat = this.chat;
                        this.chat = chat.concat(response.data.data);
                        this.next_page_url = response.data.next_page_url;
                        this.last_page_url = response.data.last_page_url;
                    })
                    .catch(error => {
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
            },
            otherUserDetails(){
                axios.post('/get/user/details', {
                    id: this.user_chat_id
                })
                    .then(response => {
                        this.cheak_scroll = false;
                      let user_details = response.data;
                         this.other_user_name = user_details.name;
                         this.other_user_avatar = user_details.avatar;
                    })
                    .catch(error => {
                    });
            },
            pageScroll(){
                let container = document.querySelector('#scrol'); //this.$el.querySelector(".scrol");
                setTimeout(function(){
                    container.scrollTop = -container.scrollHeight;
                });
            },
        },
        watch:{

            user_chat_id(){

                this.otherUserDetails();
                this.loadUserMessages(this.user_chat_id);
            },
            chat(){
                if(this.check_scroll == true){
                this.pageScroll();
                }
            },
        },
        mounted() {
            this.authUserDetails();
            Echo.private('chat-'+this.auth_user_id)
                .listen('ChatEvent', (e) => {
                    let notification = { receiver_id: e.receiver_id , sender_id: e.sender_id , total_unread_messages: e.total_unread_messages };
                    this.$emit('is_new_message' , notification);
                    if(this.user_chat_id == e.sender_id){
                    this.chat.unshift({message: e.message , receiver_id: e.receiver_id , sender_id: e.sender_id , date: e.date});
                    }
                });
        },
    }
</script>
<style>
    .load_previous_msg{
        width: 100%;
        margin-bottom: 15px;
    }
</style>