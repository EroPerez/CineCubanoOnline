
$(document).ready(function () {
    //chat contact zone
    var conn = null;
    if (location.href.indexOf("https://") === 0) {
        var conn = new WebSocket('wss://' + window.location.hostname + ':8443');
    } else {
        conn = new WebSocket('ws://' + window.location.hostname + ':8081');
    }

    var chatApp = new Vue({
        el: '#contactChat',
        delimiters: ['{', '}'],
        data: {
            conversations: [],
            messages: [],
            lastId: -1,
            activeConversation: null,
            load: true           
        },
        computed: {
            unseen: function () {
                const numbers = this.conversations.map(item => {
                    return item.unseen;
                });
                if (numbers.length === 0)
                    return 0;
                const add = (a, b) => a + b;
                return numbers.reduce(add);
            }
        },
        methods: {
            refreshMessagees: function () {
                
                if (this.activeConversation) {
                    this.$http.post('/' + locale + '/chat/conversation/' + this.activeConversation.id + '/refresh', {
                        number: this.lastId
                    }).then(function (resp) {
                        for (var i = 0; i < resp.data.length; i++) {
                            this.messages.push(resp.data[i]);
                            this.lastId = resp.data[i].id;
                        }
                        this.activeConversation.unseen = 0;
                    });
                }
            },
            loadConversations: function () {                
                this.$http.get('/' + locale + '/chat/conversation').then(function (resp) {
                    this.conversations = resp.data;
                });
            },
            send: function () {
                var msg = $('#msg').val();
                if (msg !== "") {
                    this.messages.push({
                        msg: msg,
                        out: true,
                        sending: true
                    });
                    setTimeout(function () {
                        $('#msg').val("");
                        var wtf = $('#conversation .chat-body');
                        var height = wtf[0].scrollHeight;
                        wtf.scrollTop(height);
                    });
                    this.$http.post('/' + locale + '/chat/conversation/' + this.activeConversation.id + '/send', {
                        msg: msg,
                        number: this.messages.length - 1
                    }).then(function (resp) {
                        this.messages[resp.data.number].sending = false;
                        msg = {
                            type: 'message',
                            id: this.activeConversation.member.id,
                            msg_id: resp.data.message
                        };
                        console.log(msg);
                        conn.send(JSON.stringify(msg));
                    });
                }
            },
            activeUser: function (user_id) {
                this.$http.post('/' + locale + '/chat/conversation/create', {
                    user: user_id
                }).then(function (resp) {
                    this.conversations.push(resp.data);
                    this.active(resp.data);
                });
            },
            active: function (conversation) {
                this.activeConversation = conversation;
                this.load = true;
                conversation.unseen = 0;
                $('#conversation').show(100);
                $('#chat').addClass('o-h');
                $('#conversations').hide(100);
                this.messages = [];
                this.$http.get('/' + locale + '/chat/conversation/' + conversation.id).then(function (resp) {
                    this.messages = resp.data;
                    if (resp.data.length > 0) {
                        this.lastId = resp.data[resp.data.length - 1].id;
                    }
                    this.load = false;
                    setTimeout(function () {
                        var wtf = $('#conversation .chat-body');
                        var height = wtf[0].scrollHeight;
                        wtf.scrollTop(height);
                    });
                });
            },
            back: function () {
                this.activeConversation = null;
                this.lastId = -1;
                $('#conversations').show(100);
                $('#conversation').hide(100);
                $('#chat').removeClass('o-h');
            }
        },
        mounted: function () {
            this.loadConversations();
        }
    });

    open = false;
    setTimeout(function () {
        height = Number($('#chat').height()) - 160;
        $('#conversation .chat-body').height(height);
    });

    $('#close').hide().removeClass('hide');
    $('#chat').hide().removeClass('hide');
    $('#conversation').hide();
    $('.chat-btn').click(function () {
        changeChat();
    });

    changeChat = function () {
        if (!open) {
            $('#open').hide(100);
            $('#close').show(100);
            $('#chat').show(100);
        } else {
            $('#open').show(100);
            $('#close').hide(100);
            $('#chat').hide(100);
        }
        open = !open;
    };

// START SOCKET CONFIG
    /**
     * Note that you need to change the "sandbox" for the URL of your project.
     * According to the configuration in Sockets/Chat.php , change the port if you need to.
     * @type WebSocket
     */
    conn.onopen = function (e) {
        conn.send(JSON.stringify({
            type: 'status',
            id: window.user_id
        }));
    };

    conn.onmessage = function (e) {
        var data = JSON.parse(e.data);
        if (chatApp.activeConversation && chatApp.activeConversation.id === data.conversation_id) {
            chatApp.messages.push(data.message);
            chatApp.$http.post('/' + locale + '/chat/seen/' + data.message.id);
            setTimeout(function () {
                var wtf = $('#conversation .chat-body');
                var height = wtf[0].scrollHeight;
                wtf.scrollTop(height);
            });
            if (!open) {
                changeChat();
            }

        } else {
            chatApp.loadConversations();
        }
    };

    conn.onerror = function (e) {
        refreshChat = 60 * 1000;

        //refresh conversation
        rConv = function () {
            chatApp.loadConversations()
            setTimeout(rConv, refreshChat);
        };

        //refresh message
        rMes = function () {
            chatApp.refreshMessagees();
            setTimeout(rMes, refreshChat);
        };

        rMes();
        rConv();
    };
// END SOCKET CONFIG

    $("#des").bind('click', function () {
        $(".member-des").toggleClass("hide");
    });
});