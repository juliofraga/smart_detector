<template>
    <div class="container">
        {{ settings}}
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                settings: {data: {}},
                urlBase: utils.API_URL + '/api/v1/system-settings',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false,
            }
        },
        methods: {
            loadList() {
                let url = this.urlBase;
                utils.axiosGet(url, this, 'settings');
                utils.cleanFeedbackMessage(this);                    
            },
        },
        mounted() {
            this.loadList();
        }
    }
</script>
