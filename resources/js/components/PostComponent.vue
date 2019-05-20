<template id="post-component">
    <div v-bind:class="{ solved: (state.id == 2), closed: (state.id == 3) }" class="pane">
        <div class="row mb-2">
            <div class="col">
                <span v-if="user" class="mr-2"><img class="border rounded-circle" :src="user.avatar_small_url" width="30" /></span>
                <span class="h6">
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                    <a class="mr-2" href="#" v-if="user">@{{ user.name }}</a>
                </span>

                <span v-if="!full_display">
                    <span class="mx-2">&#8226;</span>
                    <a v-if="!full_display" :href="post_view_route + '/' + id">
                         <font-awesome-icon icon="link" /> Voir
                    </a>
                </span>
            </div>

            <!-- right -->
            <div class="col-4 text-right">
                <span class="mr-2"><small>{{ date }}</small></span>
                <span v-if="circle">
                    <span v-if="this.isPublic" class="badge badge-gd-info"><font-awesome-icon icon="globe"/> Public</span>
                    <span v-else class="badge badge-gd-success"><font-awesome-icon icon="user-friends" /> {{ circle.name }}</span>
                </span>

                <span class="dropdown" v-if="isme">
                    <button class="badge badge-tool dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="cog" />
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Editer</a>
                        <a class="dropdown-item" href="#">Supprimer</a>
                    </div>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <span class="badge badge-secondary" :data-toggle="'t-' + id" data-placement="top" :title="'W:' + weight">dev (E: {{emergency}})</span>

                <!-- tags -->
                <a class="badge badge-gd-primary mr-1" href="#" v-for="tag in tags">{{ tag.hash_name }}</a>
            </div>
        </div>
        <hr>

        <!-- content -->
        <p>{{ content }}</p>



        <!-- answers -->
        <div v-if="answers">
            <answer-component
                    v-for="answer in answersToDisplay"
                    v-bind:answer="answer">
            </answer-component>
            <div v-if="answers.length > countAnswersToDisplay && !allAnswers" class="text-center">
                <button class="btn btn-link btn-sm" @click="displayAllAnswers()">Afficher la suite</button>
            </div>
        </div>

        <hr>

        <div class="input-group">
            <input type="text"
                   class="form-control answer-box"
                   placeholder="Votre réponse rapide..."
                   aria-label="Username"
                   aria-describedby="basic-addon1"
                   v-model="currentAnswer"
                   v-on:keyup.enter="add()"
                   ref="textboxAnswer"/>

            <div class="input-group-append">
                <v-popover>
                    <!-- This will be the popover target (for the events and position) -->
                    <button>:)</button>
                    <div slot="popover">
                        <VEmojiPicker
                                :pack="pack"
                                labelSearch="Rechercher..."
                                @select="selectEmoji">
                        </VEmojiPicker>
                    </div>
                </v-popover>
            </div>


            <div class="input-group-append">
                <button class="btn btn-gd-primary" type="button" @click="add()">Répondre</button>
            </div>


        </div>
    </div>
</template>

<style lang="scss">

    .answer-box {
        border: none;
        background-color: #eeeeee;
        border-radius: 25px;
    }

    .tooltip {
        // ...
        display: block !important;
        .wrapper {
            // Reset bootstrap
            max-width: none;
            margin: 0;
            padding: 0;
        }

        &.popover {
            $color: #f9f9f9;
            max-width: none;
            .popover-inner {
                background: $color;
                color: #dadada;
                //padding: 24px;
                border-radius: 5px;
                box-shadow: 0 5px 30px rgba(black, .1);
                max-width: none;
            }

            .popover-arrow {
                border-color: $color;
            }
        }
    }

    .closed {
        border: 1px solid #d50700;
    }

    .solved {
        border: 1px solid #0dc600;
        background-color: #e3fcee;
    }
</style>

<script>
    import Vue from 'vue';
    import VTooltip from 'v-tooltip';

    Vue.directive('v-tooltip',  VTooltip);
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
            answers_route: String,
            coeff: Number,
            api_token: String,
            full_display: Boolean
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
            add: function() {
                axios.post(this.answers_route, {
                    message: this.currentAnswer,
                    post_id: this.id
                }, {
                    headers: {
                        'Authorization':'Bearer ' + this.api_token,
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
                            'Authorization':'Bearer ' + this.api_token,
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
                            'Authorization':'Bearer ' + this.api_token,
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
