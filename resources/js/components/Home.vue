<template>
    <div class="container">
        <div class="container-fluid p-4">
            <div class="mb-4">
                <h2 class="fw-bold">Painel de Monitoramento <span class="highlight">Smart Detector</span></h2>
                <p class="text-secondary">Visualize eventos em tempo real.</p>
            </div>
            <div v-if="Object.keys(events.data).length > 0">
                <event-summary-component :events="events"></event-summary-component>
                <event-table-component :data="events.data" title="Eventos Recentes"></event-table-component>
            </div>
            <div v-else-if="loaded === true">
                <no-itens-component message="Não há eventos registrados na data de hoje"></no-itens-component>
            </div>
            <div v-else-if="loaded === false">
                <spinner-component></spinner-component>
            </div>
        </div>
        <event-modal-component></event-modal-component>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                events: {data: {}},
                urlBase: utils.API_URL + '/api/v1/event',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false,
                lastId: 0,
            }
        },
        methods: {
            loadEventList() {
                let url = this.urlBase;
                axios.get(url)
                    .then(response => {
                        this.events = response;
                        if (response.data.length > 0) {
                            this.lastId = Math.max(...this.events.data.map(e => e.id));
                        }
                        this.loaded = true;
                    })
                    .catch(errors => {
                        this.feedbackTitle = "Houve um erro";
                        this.status = 'error';
                        this.feedbackMessage = errors;
                    })                  
            },
            getNewEvents() {
                let url = this.urlBase + '/new/' + this.lastId;
                if (this.lastId == 0) {
                    url = this.urlBase;
                }
                axios.get(url)
                    .then(response => {
                        if (response.data.length > 0) {
                            const merged = [...this.events.data, ...response.data].reduce((acc, current) => {
                                if (!acc.some(item => item.id === current.id)) {
                                    acc.push(current);
                                }
                                return acc;
                            }, [])
                            .sort((a, b) => new Date(b.event_date_time) - new Date(a.event_date_time));
                            this.events = {data: merged};
                            this.lastId = Math.max(...this.events.data.map(e => e.id));
                        }
                    })
                    .catch(errors => {
                        this.feedbackTitle = "Houve um erro";
                        this.status = 'error';
                        this.feedbackMessage = errors;
                    }) 
            }
        },
        mounted() {
            this.loadEventList();
            setInterval(() => {
                this.getNewEvents();
            }, 10000);
        }
    }
</script>
