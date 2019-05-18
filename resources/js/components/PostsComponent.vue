<template id="posts-component">
    <div>
        <div class="pane mb-2">
            <textarea v-model="currentPost"></textarea>
            <button @click="add()">Envoyer</button>
        </div>
        <post-component v-for="id in posts" v-bind:id="id"></post-component>
    </div>



</template>


<script>
    export default {
        data() {
            return {
                currentPost: null,
                posts: [
                    1, 1, 1, 1, 1
                ]
            }
        },

        methods: {
            add: function() {
                axios.post('api/posts', {
                    message: this.currentPost
                }, {
                    headers: {
                        'Authorization':'Bearer ' + '456',
                    }
                }).then((response) => {
                    //this.load();
                })
                    .catch((e) => {
                        console.error(e);
                    })
            },
        },

        mounted() {

        }
    }
</script>
