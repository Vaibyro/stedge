<template id="main-component">
    <div class="row">
        <div class="col" style="-ms-flex: 0 0 300px; flex: 0 0 300px;">
            <tags-component :api_token="api_token"></tags-component>
            <circles-component :api_token="api_token" :user_id="user_id"></circles-component>


        </div>

        <div class="col p-3">
            <posts-component :api_token="api_token"></posts-component>
        </div>
    </div>


</template>


<script>
    import TagsComponent from "./TagsComponent";
    export default {
        components: {TagsComponent},
        props: {
            api_token: String,
            user_id: String
        },

        data() {
            return {

            }
        },


        methods: {


        },

        mounted() {
            console.log(this.user_id);
        }
    }
</script>
