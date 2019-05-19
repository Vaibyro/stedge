<template id="main-component">
    <div class="row pt-3">
        <div class="col-lg-2 pr-lg-0">
            <sort-component :api_token="api_token" :user_id="user_id"></sort-component>
            <tags-component :api_token="api_token"></tags-component>
            <circles-component :api_token="api_token" :user_id="user_id"></circles-component>
            <state-component :api_token="api_token" :user_id="user_id"></state-component>
        </div>

        <div class="col">
            <posts-component :api_token="api_token"></posts-component>
        </div>

        <div class="col-lg-2 pl-lg-0">
            <trends-component :api_token="api_token" :user_id="user_id"></trends-component>
        </div>
    </div>


</template>

<style>
    .fixed-width {
        -ms-flex: 0 0 300px;
        flex: 0 0 300px;
    }

    .menu {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
</style>

<script>
    import TagsComponent from "./TagsComponent";
    import SortComponent from "./SortComponent";
    import StateComponent from "./StateComponent";

    export default {
        components: {StateComponent, SortComponent, TagsComponent},
        props: {
            api_token: String,
            user_id: String
        },

        data() {
            return {}
        },


        methods: {},

        mounted() {
            console.log(this.user_id);
        }
    }
</script>
