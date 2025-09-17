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
        <div class="row mt-4"  v-if="events.data.length > 0">
            <div class="col col-10">
                <paginate-component>
                    <li v-for="l, key in events.links" :key="key" class="page-item" @click="paginate(l)">
                        <div v-if="l.active">
                            <a class="page-link dark-paginantion-active" v-html="l.label" 
                            v-if="
                                key == events.current_page || 
                                key == events.current_page - 1 || 
                                key == events.current_page + 1 || 
                                key == 0 ||
                                (events.current_page == 1 && key == 3) ||
                                key == events.last_page + 1 || 
                                (events.current_page == events.last_page && key == events.last_page - 2)"
                        ></a>
                        </div>
                        <div v-else>
                            <a class="page-link dark-pagination" 
                            v-if="
                                l.url != null && 
                                (key == events.current_page || 
                                key == events.current_page - 1 || 
                                key == events.current_page + 1 || 
                                key == 0 ||
                                (events.current_page == 1 && key == 3) ||
                                key == events.last_page + 1 || 
                                (events.current_page == events.last_page && key == events.last_page - 2))"
                        >{{ l.label | formatNextPrevButton }}</a>
                        </div>
                        
                    </li>
                </paginate-component>
            </div>
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
            loadList() {
                let url = this.urlBase + '/get/all/?' + this.urlPaginate + this.urlFilter;
                axios.get(url)
                    .then(response => {
                        this.events = response.data;
                        this.loaded = true;
                    })
                    .catch(errors => {
                        this.feedbackTitle = "Houve um erro";
                        this.status = 'error';
                        this.feedbackMessage = errors;
                    })
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
            this.loadList();
        }
    }
</script>
