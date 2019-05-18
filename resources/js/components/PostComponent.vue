<template id="post-component">
    <div class="pane mb-2">

        <div class="row mb-2">
            <div class="col-6">
                <span class="h6">
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                    <a class="mr-2" href="#" v-if="user">@{{ user.name }}</a>
                </span>
                <a class="badge badge-gd-primary mr-1" href="#" v-for="tag in tags">{{ tag.hash_name }}</a>
                <span class="badge badge-danger">W: {{ weight }}</span>
                <span class="badge badge-danger">E: {{ emergency }}</span>
            </div>
            <div class="col-6 text-right">
                {{ date }}
                            </div>
        </div>

        <p>{{ content }}</p>

        <div>
            <answer-component v-for="answer in answers" v-bind:answer="answer"></answer-component>
        </div>

        <input type="text" v-model="currentAnswer"/>
        <button @click="add()">Envoyer</button>
    </div>
</template>


<script>
    import moment from 'moment'

    export default {
        props: ['id', 'coeff', 'api_token'],

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
                weight: this.coeff,
            }
        },

        methods: {
            add: function() {
                axios.post('api/answers', {
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
                    .get('api/posts/' + this.id, {
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
                    }).catch((e) => {
                    console.error(e);
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
            console.log("TOKEN : " + this.api_token);
            this.load();
        }
    }
</script>
