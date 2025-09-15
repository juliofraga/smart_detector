<template>
    <div class="container">
        <search-component 
            title="Eventos" 
            :buttons=" {
                add: {
                    show: false
                },
                search: {
                    show: true,
                    fields: ['description', 'type', 'ai_analysys', 'geographical_origin', 'request']
                },
                clear: {
                    show: true,
                }
            }" 
            placeholder="Buscar Evento"
            classSearch="event"
        ></search-component>
        <div v-if="Object.keys(events.data).length > 0">
            <event-table-component :data="events.data" title="Eventos Registrados"></event-table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhum eventi encontrado"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <event-modal-component></event-modal-component>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                events: {data: {}},
                urlBase: utils.API_URL + '/api/v1/event',
                urlPaginate: '',
                urlFilter: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false
            }
        },
        methods: {
            loadEventList() {
                let url = this.urlBase + '/get/all';
                axios.get(url)
                    .then(response => {
                        this.events = response;
                        this.loaded = true;
                    })
                    .catch(errors => {
                        this.feedbackTitle = "Houve um erro";
                        this.status = 'error';
                        this.feedbackMessage = errors;
                    })
            },
        },
        mounted() {
            this.loadEventList();
        }
    }
</script>
