<template>
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Eventos Hoje</h5>
                    <h2 class="highlight">{{ today }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Risco Alto</h5>
                    <h2 class="text-danger">{{ high }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Risco Médio</h5>
                    <h2 class="text-warning">{{ medium }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Risco Baixo</h5>
                    <h2 class="text-success">{{ low }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['events'],
        computed: {
            today() {
                return this.events.data ? this.events.data.length : 0;
            },
            high() {
                return this.events.data 
                    ? this.events.data.filter(item => item.threat_classification === "Alta").length 
                    : 0;
            },
            medium() {
                return this.events.data 
                    ? this.events.data.filter(item => item.threat_classification === "Média").length 
                    : 0;
            },
            low() {
                return this.events.data 
                    ? this.events.data.filter(item => item.threat_classification === "Baixa").length 
                    : 0;
            }
        }
    }
</script>
