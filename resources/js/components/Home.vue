<template>
    <div class="container">
        <div class="container-fluid p-4">
            <div class="mb-4">
                <h2 class="fw-bold">Painel de Monitoramento <span class="highlight">Smart Detector</span></h2>
                <p class="text-secondary">Visualize eventos em tempo real.</p>
            </div>
            <div v-if="Object.keys(events.data).length > 0">
                <event-summary-component :events="events"></event-summary-component>
                <table-component
                    :title="{
                        description: {title: 'Descrição', hidden: 'false', type:'text'},
                        ip_address: {title: 'IP Origem', hidden: 'false', type:'text'},
                        type: {title: 'Tipo', hidden: 'false', type:'text'},               
                        threat_classification: {title: 'Classificação', hidden: 'false', type:'text-badge-classification'},
                        event_date_time: {title: 'Data/Hora', hidden: 'false', type:'timestamp'}                    
                    }" 
                    :data="events.data"
                    :status="status"
                    :feedbackMessage="feedbackMessage"
                    :feedbackTitle="feedbackTitle"
                    sectionTitle="Eventos Recentes"
                ></table-component>
            </div>
            <div v-else-if="loaded === true">
                <no-itens-component message="Não há eventos registrados na data de hoje"></no-itens-component>
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
                        this.lastId = Math.max(...this.events.data.map(e => e.id));
                        this.loaded = true;
                    })
                    .catch(errors => {
                        this.feedbackTitle = "Houve um erro";
                        this.status = 'error';
                        this.feedbackMessage = errors;
                    })                  
            },
            getNewEvents() {
                let url = this.urlBase + '/get-new-events/' + this.lastId;
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
