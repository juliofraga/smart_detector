<template>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="text-center">
            <div v-if="loaded === true">
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h3>{{ translations.page_not_found }}</h3>
                <p class="text-muted">
                    {{ translations.resource_not_exists }}
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
                urlBase: utils.API_URL + '/api/translation',
			};
		},
        methods: {
            loadtranslations() {
                let url = this.urlBase + '/not_found_domain';
                utils.axiosGet(url, this, 'translations');
            }
        },
        mounted() {
            this.loadtranslations();
        }
    };
</script>
    
<style scoped>
    h1 {
        color: #4EA5FF;
    }
</style>