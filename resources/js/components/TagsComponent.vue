<template id="tags-component">
    <div class="p-1  mb-2" style="background-color: #e2ebf0; border-radius: 8px;">
        <h5>Sujets</h5>
        <ul>
            <li v-for="tag in tagsList" v-bind:key="tag.id">
                <label>
                    <input type="checkbox"
                           :id="tag.name"
                           :value="tag.id"
                           v-model="tagsFilter"
                           @change="changeTagFilter()"/> {{ tag.hash_name }}
                </label>
            </li>
        </ul>
    </div>


</template>


<script>
    export default {
        props: {
            api_token: String
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
                    .get('api/tags/', {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
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

            test: function () {
                console.log(this.tagsFilter);
            }
        },

        mounted() {
            this.load();
        }
    }
</script>
