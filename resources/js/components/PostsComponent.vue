<template id="posts-component">
    <div>
        <div class="pane mb-3" v-if="display_posting">
            <div>
                <div class="form-group">
                    <textarea-autosize
                            class="form-control question-box"
                            placeholder="Posez une question..."
                            ref="questionBox"
                            v-model="currentPost"
                            :min-height="30"
                            :max-height="350"
                    ></textarea-autosize>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-5">
                    <!-- tags -->
                    <multiselect
                            v-model="chosenTags"
                            :options="tags"
                            placeholder="Choisir vos tags"
                            :multiple="true"
                            :taggable="true"
                            label="name"
                            track-by="id"
                            @tag="addTag"
                    ></multiselect>
                </div>
                <div class="col">
                    <!-- circles -->
                    <multiselect
                            v-model="chosenCircle"
                            :options="circles"
                            placeholder="Public"
                            label="name"
                            track-by="id"
                            :searchable="false"
                    ></multiselect>
                </div>
                <div class="col-3 text-right">
                    <v-popover class="d-inline-block">
                        <button class="btn btn-gd-primary">
                            <font-awesome-icon icon="smile"/>
                        </button>
                        <div slot="popover">
                            <VEmojiPicker
                                    :pack="pack"
                                    labelSearch="Rechercher..."
                                    @select="selectEmoji">
                            </VEmojiPicker>
                        </div>
                    </v-popover>
                    <button class="btn btn-gd-primary" @click="add()">Envoyer</button>
                </div>
            </div>

        </div>

        <!-- posts -->
        <div ref="posts">
            <transition-group name="slide-fade">
                <post-component
                        :likes_route="likes_route"
                        v-for="post in currentPosts"
                        v-bind:key="post.id"
                        v-bind:id="post.id"
                        v-bind:coeff="post.coeff"
                        :posts_route="posts_route"
                        :users_info_route="users_info_route"
                        :feed_route="feed_route"
                        :post_view_route="post_view_route"
                        :answers_route="answers_route"
                        :full_display="false"
                        v-bind:api_token="api_token"
                        class="mb-3">
                </post-component>
            </transition-group>
        </div>

        <div class="text-center">
            <hr>
            Fin des sujets
        </div>

    </div>
</template>


<script>
    import PostComponent from "./PostComponent";
    import Multiselect from 'vue-multiselect'
    import VueTextareaAutosize from 'vue-textarea-autosize'
    import packData from 'v-emoji-picker/data/emojis.json';

    Vue.component('multiselect', Multiselect);
    export default {
        props: {
            api_token: String,
            user_id: String,
            posts_route: String,
            feed_route: String,
            post_view_route: String,
            answers_route: String,
            users_route: String,
            tags_route: String,
            likes_route: String,
            tagsFilter: Array,
            users_info_route: String,
            display_posting: {
                default: true,
                type: Boolean
            },
            user_filter: {
                default: null,
                type: String
            }
        },

        data() {
            return {
                currentPost: '',
                posts: [],
                intialPostsCount: 5,
                currentPosts: [],
                newPostsAmount: 5,
                currentCount: this.currentPosts,
                circleFilter: [],
                tagFilter: [],
                sortMethod: 'coeff_desc',
                stateFilter: '',

                // New post (tags) :
                chosenTags: [], // List of chosen tags
                tags: [], // List of loaded tags,
                initialTags: [],

                // Circles
                circles: [],
                chosenCircle: null,

                // Emoji
                pack: packData, // emoji
            }
        },

        methods: {

            /**
             * Click on emoji keyboard
             * @param emoji
             */
            selectEmoji(emoji) {
                let tbox = this.$refs.questionBox.$el;
                let emojiCode = emoji.emoji;
                let cursorPosition = tbox.selectionStart;

                this.currentPost = this.currentPost.substring(0, cursorPosition)
                    + emojiCode
                    + this.currentPost.substring(cursorPosition, this.currentPost.length);
                tbox.focus();

                // Set the position
                this.$nextTick(() => {
                    tbox.selectionEnd = cursorPosition + emojiCode.length;
                });
            },

            /**
             * Add a tag
             */
            addTag(newTag) {
                const tag = {
                    name: newTag,
                    id: -1
                };
                this.tags.push(tag);
                this.chosenTags.push(tag);
            },

            /**
             * Method to handle the scrolling.
             */
            scroll() {
                window.onscroll = () => {
                    let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

                    if (bottomOfWindow) {
                        console.log('Scrolled to bottom');
                        this.loadNewPosts(1);
                    }
                }
            },

            add: function () {
                // Tags
                let tags = [];
                let newTags = [];
                for (let i = 0; i < this.chosenTags.length; i++) {
                    let tag = this.chosenTags[i];
                    if (tag.id === -1) {
                        newTags.push(tag.name);
                    } else {
                        tags.push(tag.id);
                    }
                }

                // Sending the post
                axios.post(this.posts_route, {
                    message: this.currentPost,
                    tags: tags,
                    new_tags: newTags,
                    circle: this.chosenCircle ? this.chosenCircle.id : null
                }, {
                    headers: {
                        'Authorization': 'Bearer ' + this.api_token,
                    }
                }).then((response) => {
                    let newId = response.data.id;
                    let ComponentClass = Vue.extend(PostComponent);
                    let instance = new ComponentClass({
                        propsData: {
                            id: newId,
                            key: newId,
                            coeff: 1,
                            api_token: this.api_token,
                            likes_route: this.likes_route,
                            users_info_route: this.users_info_route,
                            feed_route: this.feed_route,
                            answers_route: this.answers_route,
                            full_display: false,
                            posts_route: this.posts_route
                        }
                    });
                    instance.$mount(); // pass nothing
                    instance.$el.classList.add('mb-3');
                    this.$refs.posts.insertBefore(instance.$el, this.$refs.posts.firstChild);

                    // Wipe
                    this.currentPost = '';
                    this.chosenTags = [];
                    this.chosenCircle = null;

                    // Refresh the tags combobox
                    this.tags = [...this.initialTags].concat(response.data.new_tags);
                }).catch((e) => {
                    console.error(e.response);
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
                // Load posts :
                axios
                    .get(this.feed_route, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
                        },
                        params: {
                            'circles': this.circleFilter.join(','),
                            'tags': this.tagFilter.join(','),
                            'sort': this.sortMethod,
                            'user_id': this.user_filter,
                            'state': this.stateFilter
                        }
                    })
                    .then(response => {
                        this.posts = response.data.data.posts;
                        if (this.posts.length < this.intialPostsCount) {
                            this.currentPosts = this.posts.slice(0, this.posts.length);
                            this.currentCount = this.posts.length;
                        } else {
                            this.currentPosts = this.posts.slice(0, this.intialPostsCount);
                            this.currentCount = this.intialPostsCount;
                        }
                    }).catch((e) => {
                    console.log(e.response);
                });

                // Load tags
                axios
                    .get(this.tags_route, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
                        }
                    })
                    .then(response => {
                        this.tags = response.data.data;
                        this.initialTags = [...this.tags];

                    }).catch((e) => {
                    console.log(e.response);
                });

                // Load circles
                axios
                    .get(this.users_route + '/' + this.user_id, {
                        headers: {
                            'Authorization': 'Bearer ' + this.api_token,
                        },
                    })
                    .then(response => {
                        this.circles = response.data.data.circles.data;
                    }).catch((e) => {
                    console.error(e.response);

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

            this.$root.$on('changeStateFilter', (stateFilter) => {
                this.stateFilter = stateFilter;
                this.initialLoad();
            });
        }
    }
</script>
