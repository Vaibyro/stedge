<template id="answer-component">
    <div class="pane-answer mb-2">

        <div class="row">
            <div class="col-6">
                <span class="bold" v-if="user">{{ user.firstname }} {{ user.lastname }}</span>
                <a href="#" v-if="user">@{{ user.name }}</a></div>
            <div class="col-6 text-right">{{ date }}</div>
        </div>

        {{ content }}
    </div>
</template>


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
