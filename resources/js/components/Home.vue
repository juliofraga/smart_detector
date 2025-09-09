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
                <no-itens-component></no-itens-component>
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
            }
        },
        methods: {
            loadEventList() {
                let url = this.urlBase;
                axios.get(url)
                    .then(response => {
                        this.events = response;
                        this.loaded = true;
                    })
                    .catch(errors => {
                        if (errors.response.status == 500) {
                            this.feedbackTitle = "Erro no servidor";
                            this.status = 'error';
                            this.feedbackMessage = {message: "Desculpe, não conseguimos processar a sua requisição, tente novamente ou entre em contato com a equipe de suporte"}
                        } else {
                            this.feedbackTitle = "Houve um erro";
                            this.status = 'error';
                            this.feedbackMessage = errors;
                        }
                    })                  
            },
        },
        mounted() {
            this.loadEventList();
        }
    }
</script>
