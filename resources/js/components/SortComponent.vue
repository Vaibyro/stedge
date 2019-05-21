<template id="sort-component">
    <div class="lateral-pane">
        <h6><font-awesome-icon icon="sort"/> Tri</h6>
        <hr>
        <div class="form-group">
            <select id="sort-dropbox" class="custom-select" v-model="sortMethod"
                    @change="changeSortMethod()">
                <option value="coeff_desc">Pertinence</option>
                <option value="date_asc">Du plus vieux au plus récent</option>
                <option value="date_desc">Du plus récent au plus vieux</option>
                <option value="coeff_asc">Les moins importants d'abord</option>
            </select>
            <label hidden="hidden" for="sort-dropbox"></label>
        </div>


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
                sortMethod: 'coeff_desc'
            }
        },


        methods: {
            /**
             * Load the tags list
             */
            load: function () {
            },

            changeSortMethod: function () {
                this.$root.$emit('changeSortMethod', this.sortMethod);
            },
        },

        mounted() {
            this.load();
        }
    }
</script>
