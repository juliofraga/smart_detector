<template>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="text-center">
            <div v-if="loaded === true">
                <h1 class="display-1 fw-bold text-danger">403</h1>
                <h3>{{ translations.access_denied }}</h3>
                <p class="text-muted">
                    {{ translations.you_dont_have_permisson }}
                </p>
                <a href="/home" class="btn btn-outline-primary">
                    {{ translations.back }}
                </a>
            </div>
            <div v-else-if="loaded === false">
                <spinner-component></spinner-component>
            </div>
        </div>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
			return {
				translations: {},
                loaded: false,
                urlBaseTranslation: utils.API_URL + '/api/translation',
			};
		},
        mounted() {
            utils.loadTranslations(this, 'access_denied_domain', 'translations');
        }
    };
</script>

<style scoped>
    h1 {
        color: #D65979;
    }
</style>