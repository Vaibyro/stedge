<template id="posts-component">
    <div>
        <div class="pane mb-2">
            <h6><font-awesome-icon icon="plus" /> Nouveau sujet</h6>
            <hr>
            <div>
                <div class="form-group">
                    <textarea class="form-control" v-model="currentPost" placeholder="Posez une question..."></textarea>
                </div>

                <vue-tags-input
                        v-model="tag"
                        :tags="tags"
                        @tags-changed="newTags => tags = newTags">
                </vue-tags-input>
            </div>
            <div>
                <button class="btn btn-gd-primary" @click="add()">Envoyer</button>
            </div>

        </div>

        <div ref="posts">
            <transition-group name="slide-fade">
                <post-component
                        v-for="post in currentPosts"
                        v-bind:key="post.id"
                        v-bind:id="post.id"
                        v-bind:coeff="post.coeff"
                        v-bind:api_token="api_token">
                 </post-component>
            </transition-group>
        </div>

        <div class="text-center">
            Fin du fil d'actualité
        </div>

    </div>
</template>


<script>
    import PostComponent from "./PostComponent";
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
        props: {
            api_token: String,
            tagsFilter: Array
        },

        data() {
            return {
                currentPost: null,
                posts: [],
                intialPostsCount: 5,
                currentPosts: [],
                newPostsAmount: 5,
                currentCount: this.currentPosts,
                circleFilter: [],
                tagFilter: [],
                sortMethod: 'coeff_desc'
            }
        },



        methods: {
            /**
             * Method to handle the scrolling.
             */
            scroll () {
                window.onscroll = () => {
                    let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

                    if (bottomOfWindow) {
                        console.log('Scrolled to bottom');
                        this.loadNewPosts(1);
                    }
                }
            },

            add: function() {
                axios.post('api/posts', {
                    message: this.currentPost
                }, {
                    headers: {
                        'Authorization':'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    let newId = response.data.id;
                    let ComponentClass = Vue.extend(PostComponent);
                    let instance = new ComponentClass({
                        propsData: { id: newId, key: newId, coeff: 1, api_token: this.api_token }
                    });
                    instance.$mount(); // pass nothing
                    this.$refs.posts.insertBefore(instance.$el, this.$refs.posts.firstChild);
                })
                    .catch((e) => {
                        console.error(e);
                    })
            },

            /**
             * Method to load new posts (when user scroll to the bottom).
             */
            loadNewPosts: function () {
                let max = this.currentCount + this.newPostsAmount > this.posts.length ?
                    this.posts.length : this.currentCount + this.newPostsAmount;

                for (let i = this.currentCount; i < max; i++) {
                    this.currentPosts.push(this.posts[i]);
                }
                this.currentCount += this.newPostsAmount;
            },

            /**
             * Initial load of the posts.
             */
            initialLoad: function () {
                axios
                    .get('api/feed/', {
                        headers: {
                            'Authorization':'Bearer ' + this.api_token,
                        },
                        params: {
                            'circles': this.circleFilter.join(','),
                            'tags': this.tagFilter.join(','),
                            'sort': this.sortMethod
                        }
                    })
                    .then(response => {
                        console.log(response);
                        this.posts = response.data.data.posts;
                        if (this.posts.length < this.intialPostsCount) {
                            this.currentPosts = this.posts.slice(0, this.posts.length);
                            this.currentCount = this.posts.length;
                        } else {
                            this.currentPosts = this.posts.slice(0, this.intialPostsCount);
                            this.currentCount = this.intialPostsCount;
                        }

                    }).catch((e) => {
                    console.error(e);

                })
            }
        },

        mounted() {
            this.scroll();
            this.initialLoad();

            this.$root.$on('changeTagFilter', (tagsList) => {
                this.tagFilter = tagsList;
                this.initialLoad();
            });

            this.$root.$on('changeCircleFilter', (circlesList) => {
                this.circleFilter = circlesList;
                this.initialLoad();
            });

            this.$root.$on('changeSortMethod', (sortMethod) => {
                this.sortMethod = sortMethod;
                this.initialLoad();
            });
        }
    }
</script>
