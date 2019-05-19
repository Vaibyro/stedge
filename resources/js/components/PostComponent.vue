<template id="post-component">
    <div v-bind:class="{ solved: (state.id == 2), closed: (state.id == 3) }" class="pane mb-2">
        <div class="row mb-2">
            <div class="col">
                <span v-if="user" class="mr-2"><img class="border rounded-circle" :src="user.avatar_small_url" width="30" /></span>
                <span class="h6">
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                    <a class="mr-2" href="#" v-if="user">@{{ user.name }}</a>
                </span>
                <a class="badge badge-gd-primary mr-1" href="#" v-for="tag in tags">{{ tag.hash_name }}</a>
                <span class="badge badge-danger">W: {{ weight }}</span>
                <span class="badge badge-danger">E: {{ emergency }}</span>
            </div>
            <div class="col-4 text-right">
                <span class="mr-2">{{ date }}</span>
                <span v-if="circle">
                    <span v-if="this.isPublic" class="badge badge-gd-info"><font-awesome-icon icon="globe"/> Public</span>
                    <span v-else class="badge badge-gd-success"><font-awesome-icon icon="user-friends" /> {{ circle.name }}</span>
                </span>

                <span class="dropdown" v-if="isme">
                    <button class="badge badge-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="cog" />
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Editer</a>
                        <a class="dropdown-item" href="#">Supprimer</a>
                    </div>
                </span>
            </div>
        </div>
        <hr>

        <p>{{ content }}</p>

        <!-- answers -->
        <div>
            <answer-component v-for="answer in answers" v-bind:answer="answer"></answer-component>

            <div v-if="answers.length > 0">
                <a href="">Afficher la suite</a>
            </div>

        </div>

        <hr>

        <div class="input-group">
            <input type="text" class="form-control" placeholder="Votre réponse rapide..." aria-label="Username" aria-describedby="basic-addon1" v-model="currentAnswer">
            <div class="input-group-append">
                <button class="btn btn-gd-primary" type="button" @click="add()">:)</button>
            </div>

            <div class="input-group-append">
                <button class="btn btn-gd-primary" type="button" @click="add()">Répondre</button>
            </div>
        </div>

    </div>
</template>

<style>
    .closed {
        border: 1px solid #d50700;
    }

    .solved {
        border: 1px solid #0dc600;
    }
</style>

<script>
    import moment from 'moment'
    moment.locale('fr');

    export default {
        props: {
            id: Number,
            posts_route: String,
            answers_route: String,
            coeff: Number,
            api_token: String,
            full_display: Boolean
        },

        data() {
            return {
                test: null,
                user: null,
                content: null,
                date: null,
                tags: null,
                answers: null,
                emergency: null,
                currentAnswer: null,
                state: 1,
                weight: this.coeff,
                isPublic: false,
                circle: null,
                isme: false,
            }
        },

        methods: {
            add: function() {
                axios.post(this.answers_route, {
                    message: this.currentAnswer,
                    post_id: this.id
                }, {
                    headers: {
                        'Authorization':'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    this.load();
                    this.reloadCoeff();
                })
                    .catch((e) => {
                        console.error(e);
                    })
            },
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
                        this.answers = data.answers.data;
                        this.isPublic = data.is_public;
                        this.circle = data.circle;
                        this.isme = data.is_it_me;
                        this.state = data.state;
                    }).catch((e) => {
                    console.error(e.response);
                })
            },
            reloadCoeff: function () {
                axios
                    .get('api/feed/' + this.id, {
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
        }
    }
</script>
