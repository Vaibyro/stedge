<template id="circles-component">
    <div class="pane mb-2">
        <h5> <font-awesome-icon icon="users" /> Cercles</h5>
        <ul  class="menu">
            <li v-for="circle in circlesList" v-bind:key="circle.id">
                <div class="custom-control custom-checkbox mr-sm-2">
                    <input class="custom-control-input" type="checkbox"
                           :id="circle.name"
                           :value="circle.id"
                           v-model="circlesFilter"
                           @change="changeCircleFilter()"/>
                    <label class="custom-control-label" :for="circle.name">{{ circle.name }}</label>
                </div>

            </li>
        </ul>
        <a class="" href="circles">Gérer mes cercles</a>
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
                circlesFilter: [],
                circlesList: []
            }
        },


        methods: {
            /**
             * Load the tags list
             */
            load: function () {
                axios
                    .get('api/users/' + this.user_id, {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        },
                    })
                    .then(response => {
                        this.circlesList = response.data.data.circles.data;
                    }).catch((e) => {
                    console.error(e);

                })
            },

            changeCircleFilter: function () {
                this.$root.$emit('changeCircleFilter', this.circlesFilter)
            },

            test: function () {
                console.log(this.circlesFilter);
            }
        },

        mounted() {
            this.load();
        }
    }
</script>
