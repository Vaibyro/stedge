<template id="answer-component">
    <div class="pane-answer mb-2" v-bind:class="{ 'pane-best-answer': answer.is_best_answer }">

        <div class="row">
            <div class="col">
                <span v-if="user"><img class="border rounded-circle mr-2" :src="user.avatar_small_url" width="40"/></span>
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                <!-- todo user page -->
                <a :href="users_info_route + '/' + user.id" v-if="user">@{{ user.name }}</a>

                <!-- likes bar -->
                <div class="ml-2 d-inline" v-if="!frozen">
                    <button class="badge badge-tool" v-if="answer && answer.can_approbe" @click="approbe()">
                        <font-awesome-icon icon="check"/>
                        Approuver
                    </button>
                    <span class="btn-group-toggle">
                        <label class="badge badge-tool" v-bind:class="{ active: liked }">
                            <input type="checkbox" autocomplete="off" @change="togglelike()"/>
                            <font-awesome-icon icon="heart"/> {{ likes }}
                        </label>
                    </span>
                </div>
                <div class="ml-2 d-inline" v-else>
                    <small><font-awesome-icon icon="heart"/> {{ likes }}</small>
                </div>
            </div>
            <div class="col-3 text-right">
                <small>{{ date }}</small>
                <span class="dropdown" v-if="user && user.is_it_me && !frozen">
                    <button class="badge badge-tool dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="cog"/>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <!-- <a class="dropdown-item" href="#">Editer</a> -->
                        <a class="dropdown-item" href="#" @click="removeAnswer()">Supprimer</a>
                    </div>
                </span>
            </div>
        </div>
        <hr>
        {{ content }}


    </div>
</template>

<style>
    .pane-answer {
        border: 2px solid rgba(0, 0, 0, 0.08);
        background-color: rgba(0, 0, 0, 0.03);
        border-radius: 3px;
        padding: 8px;
    }

    .pane-best-answer {
        border-color: #0bb468;
    }

</style>

<script>
    import moment from 'moment'

    export default {
        props: {
            answer: Object,
            likes_route: String,
            answers_route: String,
            users_info_route: String,
            posts_route: String,
            api_token: null,
            frozen: {
                default: false,
                type: Boolean
            },
        },

        data() {
            return {
                content: null,
                date: null,
                user: null,
                likes: 0,
                liked: false,
            }
        },

        methods: {
            togglelike: function () {
                if (this.liked) {
                    this.removeLike();
                } else {
                    this.putLike();
                }
            },
            putLike: function () {
                axios.post(this.likes_route, {
                    answer_id: this.answer.id
                }, {
                    headers: {
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    this.liked = true;
                    this.likes++;
                }).catch((e) => {
                    console.error(e);
                });
            },
            removeLike: function () {
                axios.delete(this.likes_route + '/' + this.answer.id, {
                    headers: {
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    this.liked = false;
                    this.likes--;
                }).catch((e) => {
                    console.error(e);
                });
            },
            removeAnswer: function () {
                axios.delete(this.answers_route + '/' + this.answer.id, {
                    headers: {
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    this.$destroy();
                    this.$el.parentNode.removeChild(this.$el);
                }).catch((e) => {
                    console.error(e.response);
                })
            },
            approbe: function () {
                let postId = this.answer.post_id;
                axios.post(this.posts_route + '/' + postId + '/best', {
                    answer_id: this.answer.id
                }, {
                    headers: {
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    console.log("answer approbated");
                    this.$emit('approbation');
                }).catch((e) => {
                    console.error(e.response);
                });
            }
        },

        mounted() {
            console.log('Answer Component mounted.');
            this.content = this.answer.content;
            this.user = this.answer.user;
            this.date = moment(this.answer.created_at).fromNow();
            this.likes = this.answer.likes;
            this.liked = this.answer.liked_by_me;
        }
    }
</script>
