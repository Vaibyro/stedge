<template id="trends-component">
    <div class="lateral-pane">
        <h5>
            <font-awesome-icon icon="poll"/>
            A la une
        </h5>
        <hr>
        <div>
            <div class="row">
                <div class="col">
                    <span class="bold">Catégorie</span>
                </div>
                <div class="col text-right">
                    <span class="bold"># Sujets</span>
                </div>
            </div>
            <div class="row" v-for="trend in trendsList" v-bind:key="trend.id">
                <div class="col">
                    <font-awesome-icon icon="hashtag"/>
                    {{ trend.name }}
                </div>
                <div class="col text-right">
                    {{ trend.posts_count }}
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            api_token: String,
            trends_route: String,
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
                    .get(this.trends_route, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
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
