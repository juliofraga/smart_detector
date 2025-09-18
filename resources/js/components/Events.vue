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
                    fields: ['description', 'geographical_origin', 'request']
                },
                clear: {
                    show: true,
                },
                searchDate: {
                    show: true,
                    field: 'event_date_time'
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
        <paginate-component :data = "events"></paginate-component>
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
            loadList() {
                let url = this.urlBase + '/get/all/?' + this.urlPaginate + this.urlFilter;
                utils.axiosGet(url, this, 'events');
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            paginate(l) {
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadList();
                }
            },
        },
        mounted() {
            EventBus.$on("loadList", this.loadList)
            EventBus.$on("setUrlFilter", this.setUrlFilter);
            EventBus.$on("paginate", this.paginate);
            this.loadList();
        }
    }
</script>
