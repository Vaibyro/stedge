<template id="tags-component">
    <div class="pane mb-2 w-100">
        <h5>
            <font-awesome-icon icon="hashtag"/>
            Sujets
        </h5>
        <hr>
        <ul class="menu">
            <li v-for="tag in tagsList" v-bind:key="tag.id">
                <div class="custom-control custom-checkbox mr-sm-2">
                    <input class="custom-control-input" type="checkbox"
                           :id="tag.name"
                           :value="tag.id"
                           v-model="tagsFilter"
                           @change="changeTagFilter()"/>
                    <label class="custom-control-label" :for="tag.name">{{ tag.hash_name }}</label>
                </div>
            </li>
        </ul>
    </div>


</template>

<style>

</style>

<script>
    export default {
        props: {
            api_token: String,
            tags_route: String
        },

        data() {
            return {
                tagsFilter: [],
                tagsList: []
            }
        },


        methods: {
            /**
             * Load the tags list
             */
            load: function () {
                axios
                    .get(this.tags_route, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
                        },
                        params: {
                            'only_official': true
                        }
                    })
                    .then(response => {
                        this.tagsList = response.data.data;
                    }).catch((e) => {
                    console.error(e);

                })
            },

            changeTagFilter: function () {
                this.$root.$emit('changeTagFilter', this.tagsFilter)
            },
        },

        mounted() {
            this.load();
        }
    }
</script>
