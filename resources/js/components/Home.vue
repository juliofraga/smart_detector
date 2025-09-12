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
                        event_date_time: {title: 'Data/Hora', hidden: 'false', type:'timestamp'},
                        id: {hidden: 'true'},
                        ai_analysys: {hidden: 'true'},
                        geographical_origin: {hidden: 'true'},
                        request: {hidden: 'true'},
                        detalhes: {title: 'Detalhes', hidden: 'false', type: 'buttonModal', modalId: '#modalEventDetail', buttonType: 'view'}
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
        <modal-component id="modalEventDetail" options="modal-dialog-centered modal-xl" title="Detalhes do Evento">
            <template v-slot:conteudo>
                <!-- Descrição -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Descrição</label>
                    <div class="p-3 bg-secondary rounded text-light" style="max-height: 120px; overflow-y: auto;">
                        Tentativa de injeção SQL detectada no endpoint de login, com múltiplos parâmetros suspeitos.
                    </div>
                </div>
                <!-- Classificação e Tipo -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Tipo de Ameaça</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" value="SQL Injection" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Classificação</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" value="Alta" readonly>
                    </div>
                </div>
                <!-- IP e Origem -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">IP</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" value="192.168.0.25" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Origem Geográfica</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" value="São Paulo, BR" readonly>
                    </div>
                </div>
                <!-- Data e Request -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Request</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" value="POST /api/login" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Data e Hora</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" value="2025-09-12 08:23:17" readonly>
                    </div>
                </div>
                <!-- Análise IA -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Análise da IA</label>
                    <div class="p-3 bg-secondary rounded text-light" style="max-height: 150px; overflow-y: auto;">
                        O padrão identificado corresponde a uma tentativa automatizada de exploração de vulnerabilidades de login. 
                        A probabilidade de ataque real é de 87%. Recomendação: bloquear IP temporariamente e revisar logs de acesso.
                    </div>
                </div>
            </template>
        </modal-component>
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
