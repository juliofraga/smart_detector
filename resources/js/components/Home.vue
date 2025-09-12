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
        <modal-component id="modalEventDetail" options="modal-dialog-centered modal-xl" title="Detalhes do Evento">
            <template v-slot:conteudo>
                <!-- Descrição -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Descrição</label>
                    <div class="p-3 bg-secondary rounded text-light" style="max-height: 120px; overflow-y: auto;">
                        {{ $store.state.item.description }}
                    </div>
                </div>
                <!-- Classificação e Tipo -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Tipo de Ameaça</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" v-model="$store.state.item.type" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Classificação de Risco</label>
                        <input type="text" class="form-control bg-success text-light border-0" v-model="$store.state.item.threat_classification" readonly v-if="$store.state.item.threat_classification == 'Baixa'">
                        <input type="text" class="form-control bg-danger text-light border-0" v-model="$store.state.item.threat_classification" readonly v-else-if="$store.state.item.threat_classification == 'Alta'">
                        <input type="text" class="form-control bg-warning border-0" v-model="$store.state.item.threat_classification" readonly v-else-if="$store.state.item.threat_classification == 'Média'">
                        <input type="text" class="form-control bg-secondary text-light border-0" v-model="$store.state.item.threat_classification" readonly v-else>
                    </div>
                </div>
                <!-- IP e Origem -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">IP de Origem</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" v-model="$store.state.item.ip_address" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Origem Geográfica</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" v-model="$store.state.item.geographical_origin" readonly>
                    </div>
                </div>
                <!-- Data e Request -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Request</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" v-model="$store.state.item.request" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-secondary">Data e Hora</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" v-model="$store.state.item.event_date_time" readonly>
                    </div>
                </div>
                <!-- Análise IA -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Análise da IA</label>
                    <textarea class="form-control bg-secondary rounded text-light border-0" rows="10" v-model="$store.state.item.ai_analysys" style="height: auto;" readonly></textarea>
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
