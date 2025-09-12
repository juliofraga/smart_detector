<template>
    <div class="container">
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
    export default {
    }
</script>
