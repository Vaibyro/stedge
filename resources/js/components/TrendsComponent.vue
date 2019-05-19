<template id="trends-component">
    <div class="pane mb-2">
        <h5>
            <font-awesome-icon icon="poll"/>
            A la une
        </h5>
        <hr>
        <ul  class="menu">
            <li v-for="trend in trendsList" v-bind:key="trend.id">
                {{ trend.name }} {{ trend.posts_count }}
            </li>
        </ul>
        <a class="" href="circles">Voir plus</a>


    </div>


</template>


<script>
    export default {
        props: {
            api_token: String,
            user_id: String
        },

        data() {
            return {
                trendsList: []
            }
        },

        methods: {
            /**
             * Load the trends list
             */
            load: function () {
                axios
                    .get('api/tags/trends', {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        },
                    })
                    .then(response => {
                        this.trendsList = response.data.data;
                    }).catch((e) => {
                    console.error(e);

                })
            },
        },

        mounted() {
            this.load();
        }
    }
</script>
