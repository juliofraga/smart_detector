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
                <!--<div class="card p-3">
                    <h5 class="mb-3">Eventos Recentes</h5>
                    <div class="table-responsive">
                        <table class="table table-dark-custom table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>IP Origem</th>
                                    <th>Tipo</th>
                                    <th>Classificação</th>
                                    <th>Data/Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                    <td>192.168.0.15</td>
                                    <td>Brute Force</td>
                                    <td><span class="badge badge-danger">ALTO RISCO</span></td>
                                    <td>2025-08-28 19:22</td>
                                </tr>
                                <tr>
                                    <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                    <td>201.55.23.87</td>
                                    <td>SQL Injection</td>
                                    <td><span class="badge badge-warning">MÉDIO RISCO</span></td>
                                    <td>2025-08-28 19:22</td>
                                </tr>
                                <tr>
                                    <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                    <td>45.12.98.10</td>
                                    <td>Acesso</td>
                                    <td><span class="badge badge-warning">MÉDIO RISCO</span></td>
                                    <td>2025-08-28 19:22</td>
                                </tr>
                                <tr>
                                    <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                    <td>177.45.90.1</td>
                                    <td>XSS</td>
                                    <td><span class="badge badge-success">BAIXO RISCO</span></td>
                                    <td>2025-08-28 19:22</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>-->
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
