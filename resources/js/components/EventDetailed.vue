<template>
    <div class="container">
        <div v-if="loaded === false && event_id">
            <spinner-component></spinner-component>
        </div>
        <div v-else>
            <div class="mb-3">
                <alert-component type="danger" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'error'"></alert-component>
            </div>
            {{ $store.state.item.classification }}
            <!-- Descrição -->
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Descrição</label>
                <div class="p-3 bg-secondary rounded text-light" style="max-height: 120px; overflow-y: auto;">
                    {{ $store.state.item.description || event.description}}
                </div>
            </div>
            <!-- Classificação e Tipo -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Tipo de Ameaça</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.type || event.type" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Classificação de Risco</label>
                    <input type="text" :class="`form-control bg-${event.classification.visual_style} text-light border-0`" :value="$store.state.item.classification || event.classification.description" readonly v-if="event.classification && event.classification.visual_style != 'warning'">
                    <input type="text" :class="`form-control bg-${$store.state.item.classification.visual_style} text-light border-0`" :value="$store.state.item.classification.description || event.classification.description" readonly v-if="$store.state.item.classification  && $store.state.item.classification.visual_style != 'warning'">
                    <div v-if="($store.state.item.classification && $store.state.item.classification.visual_style == 'warning') || (event.classification && event.classification.visual_style == 'warning')">
                        <input type="text" class="form-control bg-warning border-0" :value="$store.state.item.classification || event.classification.description" readonly v-if="event.classification">
                        <input type="text" class="form-control bg-warning border-0`" :value="$store.state.item.classification.description || event.classification.description" readonly v-if="$store.state.item.classification">
                    </div>
                </div>
            </div>
            <!-- IP e Origem -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">IP de Origem</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.ip_address || event.ip_address" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Origem Geográfica</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.geographical_origin || event.geographical_origin" readonly>
                </div>
            </div>
            <!-- Data e Request -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Request</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.request || event.request" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Data e Hora</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.event_date_time || event.event_date_time | formatDateTimeStamp" readonly>
                </div>
            </div>
            <!-- Análise IA -->
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Análise da IA</label>
                <textarea class="form-control bg-secondary rounded text-light border-0" rows="10" :value="($store.state.item.analysys && $store.state.item.analysys.description) || (event.analysys && event.analysys.description) || ''" style="height: auto;" readonly></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        props: ['event_id'],
        data() {
            return {
                urlBase: utils.API_URL + '/api/v1/event',
                event: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false
            }
        },
        methods: {
            getEvent() {
                if (this.event_id) {
                    let url = this.urlBase + '/' + this.event_id;
                    axios.get(url)
                        .then(response => {
                            this.event = response.data;
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
                }
            }
        },
        mounted() {
            this.getEvent();
        }
    }
</script>
