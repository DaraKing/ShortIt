<template>
    <div class="short-url-content">
        <h1>Paste the URL to be shortened</h1>

        <form class="short-url-form" @submit="submitForm">
            <input v-on:input="clearError" v-if="!shortenUrlGenerated"  v-model="url" type="text" placeholder="Your website url" class="short-url-input" />
            <input v-if="shortenUrlGenerated" v-on:focus="$event.target.select()" ref="shortenUrl" readonly id="shorten-url" :value="shortenUrl" type="text" class="short-url-input" />
            <input v-if="!shortenUrlGenerated" type="submit" class="short-url-submit" value="Short it" />
            <input v-if="shortenUrlGenerated" @click="copyUrl" type="button" class="short-url-submit" value="Copy url" />
            <span class="error-message" v-if="error">{{error}}</span>
        </form>
    </div>
</template>

<script>
import constants from "../constants";

export default {
    name: "ShortUrl",
    data() {
        return {
            url: '',
            error: null,
            shortenUrl: '',
            shortenUrlGenerated: false
        }
    },
    methods: {
        clearError() {
            this.error = null;
        },
        isValidURL() {
            let expression = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/
            let regex = new RegExp(expression);

            return this.url.match(regex);
        },
        submitForm(e) {
            e.preventDefault();

            if (!this.isValidURL()) {
                this.error = constants.messages.error;
                return;
            }

            this.axios.post(constants.routes.generate, {url: this.url}).then((response) => {
                this.shortenUrlGenerated = true;
                this.shortenUrl = constants.hostname + response.data.code;
            })
        },
        copyUrl() {
            this.$refs.shortenUrl.focus();
            document.execCommand('copy');
        }
    }
}
</script>

<style scoped>

</style>
