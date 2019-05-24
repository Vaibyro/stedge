<template id="circle-management-component">
    <div class="pane">

        <div class="row">
            <div class="col">
                <h5 v-if="circle">{{ circle.name }}</h5>
            </div>
            <div class="col-2 text-right" v-if="circle.is_it_me">
                <a class="text-danger" href="#" @click="removeCircle()">Supprimer</a>
            </div>
        </div>



        <hr>
        <div class="d-inline-block text-center mx-2" v-for="user in usersList">
            <img class="rounded-circle" :src="user.avatar_small_url" style="width: 4.5rem;">
            <div>
                <small><a :href="user.link">@{{ user.name }}</a></small>
            </div>
            <div v-if="user.id != user_id">
                <small v-if="circle.is_it_me"><a class="text-danger" href="#" @click="removeUser(user)">Enlever</a></small>
                <small v-else>&nbsp;</small>
            </div>
            <div v-else>
                <small>Admin</small>
            </div>
        </div>
        <hr>
        <small>{{ usersList.length }} membre(s)</small>
        <div v-if="circle.is_it_me">
            <hr>
            <div class="row">
                <div class="col-4">
                <multiselect
                        v-model="chosenUser"
                        :options="users"
                        placeholder="@utilisateur"
                        label="name"
                        track-by="id"
                        :searchable="true"
                ></multiselect>
                </div>
                <div class="col">
                <button class="btn btn-gd-primary" @click="addUser()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            api_token: String,
            circles_route: String,
            users_route: String,
            circle_id: String,
            user_id: String
        },

        data() {
            return {
                users: [],
                chosenUser: null,
                usersList: [],
                circle: null
            }
        },


        methods: {
            /**
             * Load the tags list
             */
            load: function () {
                axios.get(this.circles_route + '/' + this.circle_id + '/users', {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        },
                    })
                    .then(response => {
                        this.usersList = response.data.users.data;
                        this.circle = response.data.metadata;
                    }).catch((e) => {
                    console.error(e.response);
                });

                // users
                axios.get(this.users_route, {
                    headers: {
                        'Authorization':'Bearer ' + this.api_token,
                    },
                })
                    .then(response => {
                        this.users = response.data.data;
                    }).catch((e) => {
                    console.error(e.response);
                });
            },

            removeCircle: function () {
                axios.delete(this.circles_route + '/' + this.circle_id, {
                    headers: {
                        'Authorization':'Bearer ' + this.api_token,
                    },
                })
                    .then(response => {
                        location.reload(); // todo
                    }).catch((e) => {
                    console.error(e.response);
                });
            },

            addUser: function () {
                axios.post(this.circles_route + '/' + this.circle_id + '/users', {
                        'user_id': this.chosenUser.id
                    }, {
                    headers: {
                        'Authorization':'Bearer ' + this.api_token,
                    }
                })
                    .then(response => {
                        this.load();
                    }).catch((e) => {
                    console.error(e.response);
                })
            },

            removeUser: function (user) {
                axios.delete(this.circles_route + '/' + this.circle_id + '/users', {
                        data: {
                            'user_id': user.id
                        },
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        }
                    })
                    .then(response => {
                        this.load();
                    }).catch((e) => {
                        console.error(e.response);
                })
            }
        },

        mounted() {
            this.load();
        }
    }
</script>
