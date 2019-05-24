<template id="new-circle-component">
    <div class="pane">
        <h4>Nouveau cercle</h4>
        <hr>
        <div class="input-group">
            <!-- quick answer input -->
            <input type="text"
                   class="form-control answer-box"
                   placeholder="Nom du cercle"
                   v-model="circleName"
                   aria-label="Username"
                   aria-describedby="basic-addon1"/>

            <!-- answer button -->
            <div class="input-group-append">
                <button class="btn btn-gd-primary" type="button" @click="create()">Créer</button>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            api_token: String,
            circles_route: String,
        },

        data() {
            return {
                circleName: ''
            }
        },


        methods: {
            create: function () {
                if (this.circleName !== '') {
                    axios.post(this.circles_route, {
                        'name': this.circleName
                    }, {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        }
                    })
                        .then(response => {
                            location.reload();
                        }).catch((e) => {
                        console.error(e.response);
                    })
                }
            },
        },

        mounted() {
        }
    }
</script>
