<template id="post-component">
    <div>
        <div v-bind:class="{ solved: (state.id == 2), closed: (state.id == 3) }" class="pane">
            <div class="row mb-2">
                <div class="col">
                    <span v-if="user" class="mr-2">
                        <img class="border rounded-circle" :src="user.avatar_small_url" width="30"/>
                    </span>

                    <span class="h6">
                        <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                        <a class="mr-2" :href="users_info_route + '/' + user.id" v-if="user">@{{ user.name }}</a>
                    </span>

                    <span v-if="!full_display">
                        <!-- Link if thread on the newsfeed -->
                        <span class="mx-2">&#8226;</span>
                        <a :href="post_view_route + '/' + id"><font-awesome-icon icon="link"/> Voir</a>
                    </span>
                </div>

                <!-- right -->
                <div class="col-4 text-right">
                    <span class="mr-2"><small>{{ date }}</small></span>
                    <span v-if="circle">
                        <span v-if="this.isPublic" class="badge badge-gd-info">
                            <font-awesome-icon icon="globe"/> Public
                        </span>
                        <span v-else class="badge badge-gd-success">
                            <font-awesome-icon icon="user-friends"/> {{ circle.name }}
                        </span>
                    </span>

                    <span class="dropdown" v-if="isme">
                    <button class="badge badge-tool dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="cog"/>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Editer</a>
                        <a class="dropdown-item" href="#" @click="remove()">Supprimer</a>
                    </div>
                </span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                <span class="badge badge-secondary" :data-toggle="'t-' + id" data-placement="top"
                      :title="'W:' + weight">dev (E: {{emergency}})</span>

                    <!-- tags -->
                    <span class="badge badge-gd-primary mr-1" href="#" v-for="tag in tags">{{ tag.hash_name }}</span>
                </div>
            </div>
            <hr>

            <!-- content -->
            <p>{{ content }}</p>

            <!-- answers -->
            <div v-if="answers">
                <answer-component
                        :api_token="api_token"
                        :likes_route="likes_route"
                        :posts_route="posts_route"
                        :answers_route="answers_route"
                        :frozen="state.id != 1"
                        v-for="answer in answersToDisplay"
                        v-bind:answer="answer"
                        @approbation="load()"
                ></answer-component>
                <div v-if="answers.length > countAnswersToDisplay && !allAnswers" class="text-center">
                    <button class="btn btn-link btn-sm" @click="displayAllAnswers()">Afficher la suite - {{
                        answers.length - 1 }} réponse(s)
                    </button>
                </div>
            </div>

            <div v-if="!full_display">
                <hr>

                <!-- quick answer -->
                <div class="input-group">
                    <!-- quick answer input -->
                    <input type="text"
                           class="form-control answer-box"
                           placeholder="Votre réponse rapide..."
                           aria-label="Username"
                           aria-describedby="basic-addon1"
                           v-model="currentAnswer"
                           v-on:keyup.enter="add()"
                           ref="textboxAnswer"/>

                    <!-- emoji button -->
                    <div class="input-group-append">
                        <v-popover class="input-group-text emoji-inpput-box">
                            <button>
                                <font-awesome-icon icon="smile"/>
                            </button>
                            <div slot="popover">
                                <VEmojiPicker
                                        :pack="pack"
                                        labelSearch="Rechercher..."
                                        @select="selectEmoji">
                                </VEmojiPicker>
                            </div>
                        </v-popover>
                    </div>

                    <!-- answer button -->
                    <div class="input-group-append">
                        <button class="btn btn-gd-primary" type="button" @click="add()">Répondre</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Il full answer -->
        <full-answer-box
                v-if="full_display"
                placeholder="Votre réponse..."
                @answer="otest()"
        ></full-answer-box>
    </div>
</template>

<script>
    import Vue from 'vue';
    import VTooltip from 'v-tooltip';

    Vue.directive('v-tooltip', VTooltip);
    import VEmojiPicker from 'v-emoji-picker';
    import packData from 'v-emoji-picker/data/emojis.json';
    import moment from 'moment'

    moment.locale('fr');

    export default {
        props: {
            id: Number,
            posts_route: String,
            feed_route: String,
            post_view_route: String,
            users_info_route: String,
            answers_route: String,
            coeff: Number,
            api_token: String,
            full_display: Boolean,
            likes_route: String,
        },

        data() {
            return {
                pack: packData, // emoji

                test: null,
                user: null,
                content: null,
                date: null,
                tags: null,

                answers: null,
                answersToDisplay: null,
                allAnswers: false,
                countAnswersToDisplay: 1,

                emergency: null,
                currentAnswer: '',
                state: 1,
                weight: this.coeff,
                isPublic: false,
                circle: null,
                isme: false,
            }
        },

        methods: {

            otest: function () {
                console.log("etqt");
            },

            /**
             * Click on emoji keyboard
             * @param emoji
             */
            selectEmoji(emoji) {
                let tbox = this.$refs.textboxAnswer;
                let emojiCode = emoji.emoji;
                let cursorPosition = tbox.selectionStart;
                this.currentAnswer = this.currentAnswer.substring(0, cursorPosition)
                    + emojiCode
                    + this.currentAnswer.substring(cursorPosition, this.currentAnswer.length);
                tbox.focus();

                // Set the position
                this.$nextTick(() => {
                    tbox.selectionEnd = cursorPosition + emojiCode.length;
                });
            },

            /**
             * Add an answer
             */
            add: function () {
                axios.post(this.answers_route, {
                    message: this.currentAnswer,
                    post_id: this.id
                }, {
                    headers: {
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    this.allAnswers = true;
                    this.load();
                    this.reloadCoeff();
                    this.currentAnswer = '';
                })
                    .catch((e) => {
                        console.error(e);
                    })
            },

            /**
             * Remove the post
             */
            remove: function () {
                axios.delete(this.posts_route + '/' + this.id, {
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

            /**
             * Change the answers display mode
             */
            displayAllAnswers: function () {
                this.answersToDisplay = this.answers;
                this.allAnswers = true;
            },

            /**
             * Initial loading
             */
            load: function () {
                axios
                    .get(this.posts_route + '/' + this.id, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
                        }
                    })
                    .then(response => {
                        let data = response.data.data;
                        this.content = data.content;
                        this.date = moment(data.created_at).fromNow();
                        this.user = data.user;
                        this.test = data;
                        this.emergency = data.emergency;
                        this.tags = data.tags.data;

                        // load answers
                        this.answers = data.answers.data;
                        if (this.full_display || this.allAnswers) {
                            this.answersToDisplay = this.answers;
                        } else {
                            this.answersToDisplay = this.answers.slice(0, this.countAnswersToDisplay); //todo: select the best answer
                        }

                        this.isPublic = data.is_public;
                        this.circle = data.circle;
                        this.isme = data.is_it_me;
                        this.state = data.state;
                    }).catch((e) => {
                    console.error(e.response);
                })
            },

            /**
             * Reload the priority coefficient
             */
            reloadCoeff: function () {
                axios
                    .get(this.feed_route + '/' + this.id, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
                        }
                    })
                    .then(response => {
                        this.weight = response.data.data.coeff;
                    }).catch((e) => {
                    console.error(e);
                })
            }
        },

        mounted() {
            this.load();
            $('[data-toggle="t-' + this.id + '"]').tooltip(); // todo: remove, its for dev only
        }
    }
</script>
