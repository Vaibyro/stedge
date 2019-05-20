<template id="answer-component">
    <div class="pane-answer mb-2">

        <div class="row">
            <div class="col">
                <span v-if="user"><img class="border rounded-circle" :src="user.avatar_small_url" width="30"/></span>
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                <!-- todo user page -->
                <a href="#" v-if="user">@{{ user.name }}</a>

                <span class="ml-2">
                    <button class="badge badge-tool">Approuver</button>
                    <button class="badge badge-tool"><font-awesome-icon icon="heart"/> 0</button>
                </span>
            </div>
            <div class="col-3 text-right">
                <small>{{ date }}</small>
                <span class="dropdown" v-if="user && user.is_it_me">
                    <button class="badge badge-tool dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <font-awesome-icon icon="cog"/>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Editer</a>
                        <a class="dropdown-item" href="#">Supprimer</a>
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
        border: 2px solid #ebebeb;
        background-color: #fbfbfb;
        border-radius: 3px;
        padding: 8px;
    }

    .pane-best-answer {
        border-color: #0bb468;
        color: #0c5e20;
    }

</style>

<script>
    import moment from 'moment'

    export default {
        props: ['answer'],

        data() {
            return {
                content: null,
                date: null,
                user: null
            }
        },

        mounted() {
            console.log('Answer Component mounted.');
            this.content = this.answer.content;
            this.user = this.answer.user;
            this.date = moment(this.answer.created_at).fromNow();
        }
    }
</script>
