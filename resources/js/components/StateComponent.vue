<template id="state-component">
    <div class="lateral-pane">
        <h6><font-awesome-icon icon="check" /> Etat</h6>
        <hr>
        <ul class="menu">
            <li>
                <label class="pane-c">
                    <input type="radio"
                           id="all"
                           name="state"
                           value=""
                           v-model="statesFilter"
                           @change="changeStateFilter()"
                    /> Tous
                </label>
            </li>
            <li>
                <label class="pane-c">
                    <input type="radio"
                           id="waiting"
                           name="state"
                           value="waiting"
                           v-model="statesFilter"
                           @change="changeStateFilter()"
                    /> En attente
                </label>
            </li>
            <li>
                <label class="pane-c solved">
                    <input type="radio"
                           id="solved"
                           name="state"
                           value="solved"
                           v-model="statesFilter"
                           @change="changeStateFilter()"
                    /> Résolu
                </label>
            </li>
            <li>
                <label class="pane-c closed">
                    <input type="radio"
                           id="closed"
                           name="state"
                           value="closed"
                           v-model="statesFilter"
                           @change="changeStateFilter()"
                           checked="checked"/> Verouillé
                </label>
            </li>
        </ul>
    </div>


</template>

<style>


    .pane-c {
        width: 100%;
        border-radius: 2px;
        padding: 5px 9px 5px 9px;
        border: 1px solid #cbd0d5;
        box-shadow: 0px 0px 1px 0px rgba(0,0,0,0.15);
        background-color: #ffffff;
    }

    .closed {
        border: 1px solid #d50700;
    }

    .solved {
        border: 1px solid #0dc600;
    }
</style>

<script>
    export default {
        props: {
            api_token: String,
            user_id: String
        },

        data() {
            return {
                states: [],
                statesFilter: ""
            }
        },


        methods: {
            /**
             * Load the tags list
             */
            load: function () {
                axios
                    .get('api/states/', {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        },
                    })
                    .then(response => {
                        this.states = response.data.data;
                    }).catch((e) => {
                    console.error(e);

                })
            },

            changeStateFilter: function () {
                this.$root.$emit('changeStateFilter', this.statesFilter)
            },
        },

        mounted() {
            this.load();
        }
    }
</script>
