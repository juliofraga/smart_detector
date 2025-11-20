<template>
    <div class="container">
        <div v-if="loaded === false && event_id">
            <spinner-component></spinner-component>
        </div>
        <div v-else>
            <div class="mb-3" v-if="status == 'error'">
                <alert-component type="danger" :details="feedbackMessage" :title="feedbackTitle"></alert-component>
            </div>
            {{ $store.state.item.classification }}
            <!-- Classificação -->
            <div class="mb-3" v-if="$store.state.item.intrusion_normal || (event.intrusion_normal && allEvents == 'Yes')">
                <label class="form-label fw-bold text-secondary">Classificação</label>
                <div :class="intrusionClass" style="max-height: 120px; overflow-y: auto;">
                    {{ ($store.state.item.intrusion_normal  || event.intrusion_normal) | formatIntrusionNormalField }}
                </div>
            </div>
            <!-- Descrição -->
            <div class="mb-3">
                <label class="form-label fw-bold text-secondary">Descrição</label>
                <div class="p-3 bg-secondary rounded text-light" style="max-height: 120px; overflow-y: auto;">
                    {{ $store.state.item.description || event.description }}
                </div>
            </div>
            <!-- Classificação e Tipo -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Tipo de Ameaça</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="($store.state.item.type && $store.state.item.type.description) || (event.type && event.type.description)" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Classificação de Risco</label>
                    <input type="text" :class="`form-control bg-${event.classification.visual_style} text-light border-0`" :value="$store.state.item.classification || event.classification.description" readonly v-if="event.classification && event.classification.visual_style != 'warning'">
                    <input type="text" :class="`form-control bg-${$store.state.item.classification.visual_style} text-light border-0`" :value="$store.state.item.classification.description || event.classification.description" readonly v-if="$store.state.item.classification  && $store.state.item.classification.visual_style != 'warning'">
                    <input type="text" class="form-control bg-secondary text-light border-0" v-if="!$store.state.item.classification && !event.classification" readonly>
                    <div v-if="($store.state.item.classification && $store.state.item.classification.visual_style == 'warning') || (event.classification && event.classification.visual_style == 'warning')">
                        <input type="text" class="form-control bg-warning border-0" :value="$store.state.item.classification || event.classification.description" readonly v-if="event.classification">
                        <input type="text" class="form-control bg-warning border-0`" :value="$store.state.item.classification.description || event.classification.description" readonly v-if="$store.state.item.classification">
                    </div>
                </div>
            </div>
            <!-- IP de Origem e Data -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">IP de Origem</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.ip_address || event.ip_address" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-secondary">Data e Hora</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item.event_date_time || event.event_date_time | formatDateTime" readonly>
                </div>
            </div>
            <!-- Custom Fields -->
            <div class="row">
                <div class="col-md-6 mb-3" v-for="(meta, index) in eventMetadata" :key="index" v-if="meta.type_field == 'text'">
                    <label class="form-label fw-bold text-secondary">{{ meta.display_value }} (Custom)</label>
                    <input type="text" class="form-control bg-secondary text-light border-0" :value="$store.state.item[meta.field_name] || event[meta.field_name]" readonly>
                </div>
            </div>
            <div v-for="(meta, index) in eventMetadata" :key="index">
                <div class="mb-3" v-if="meta.type_field == 'textarea'">
                    <label class="form-label fw-bold text-secondary">{{ meta.display_value }} (Custom)</label>
                    <textarea class="form-control bg-secondary rounded text-light border-0" rows="10" :value="$store.state.item[meta.field_name] || event[meta.field_name] || ''" style="height: auto;" readonly></textarea>
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
        props: ['event_id', 'allEvents'],
        data() {
            return {
                urlBase: utils.API_URL + '/api/v1/event',
                urlBaseEventMetadata: utils.API_URL + '/api/v1/event-attribute',
                event: '',
                eventMetadata: '',
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
                    utils.axiosGet(url, this, 'event');
                }
            },
            getEventMetadata() {
                let url = this.urlBaseEventMetadata + '/show-enabled';
                utils.axiosGet(url, this, 'eventMetadata');
            }
        },
        computed: {
            intrusionValue() {
                return this.$store.state.item.intrusion_normal || this.event.intrusion_normal;
            },
            intrusionClass() {
                const value = (this.intrusionValue || '').toUpperCase();

                if (value === 'INTRUSION') return 'p-3 bg-danger rounded text-light';
                if (value === 'NORMAL') return 'p-3 bg-success rounded text-light';

                return 'bg-secondary';
            }
        },
        mounted() {
            this.getEvent();
            this.getEventMetadata();
        }
    }
</script>
