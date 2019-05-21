<template id="full-answer-box">
    <!-- full answer -->
    <div class="pane mt-3">
        <!-- answer input -->
        <textarea type="text"
                  class="form-control question-box"
                  :placeholder="placeholder"
                  v-model="content"
                  ref="textboxAnswer"
        ></textarea>

        <!-- emoji button
        <div>
            <v-popover class="input-group-text emoji-inpput-box">
                <button>
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
        </div>
        -->

        <!-- answer button -->
        <div>
            <button class="btn btn-gd-primary" type="button" @click="$emit('answer')">Répondre</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            placeholder: String,
        },

        data() {
            return {
                content: ''
            }
        },

        methods: {
        }
    }
</script>

<style scoped>

</style>