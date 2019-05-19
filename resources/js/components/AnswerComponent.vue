<template id="answer-component">
    <div class="pane-answer mb-2">

        <div class="row">
            <div class="col-6">
                <span v-if="user"><img class="border rounded-circle" :src="user.avatar_small_url" width="30" /></span>
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                <a href="#" v-if="user">@{{ user.name }}</a></div>
            <div class="col-6 text-right">
                {{ date }}
                <span class="dropdown" v-if="user && user.is_it_me">
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

        {{ content }}

        <div style="color: #d0211c">
            <font-awesome-icon icon="heart" /> 1
        </div>
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
            this.date =  moment(this.answer.created_at).fromNow();
        }
    }
</script>
