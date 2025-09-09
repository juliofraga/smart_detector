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
        data() {
            return {
                today: 0,
                high: 0,
                medium: 0,
                low: 0
            }
        },
        methods: {
            calcEvents() {
                this.today = this.events.data.length;
                this.high = this.events.data.filter(item => item.threat_classification === "Alta").length;
                this.medium = this.events.data.filter(item => item.threat_classification === "Média").length;
                this.low = this.events.data.filter(item => item.threat_classification === "Baixa").length;
            }
        },
        mounted() {
            this.calcEvents();
        }
    }
</script>
