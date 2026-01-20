<template>
    <div class="container mt-4">
        <h3 class="mb-3 text-white">{{ translations.overview }}</h3>
        <div class="row mt-4">
            <div class="col-md-12">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";
import * as utils from '../utils/functions';
export default {
    props: ['startdate', 'enddate', 'totalbyday'],
    data() {
        return {
            chart: null,
            translations: {}
        };
    },
    mounted() {
        this.applyRange();
        utils.loadTranslations(this, 'overview_dashboard_domain', 'translations');
    },
    watch: {
        totalbyday() {
            this.applyRange();
        },
    },
    methods: {
        applyRange() {
            const start = this.startdate.split("T")[0];
            const end = this.enddate.split("T")[0];
            const filtered = this.totalbyday.filter(e =>
                e.day >= start && e.day <= end
            );
            this.renderChart(filtered);
        },
        renderChart(filtered) {
            if (this.chart) this.chart.destroy();
            this.chart = new Chart(document.getElementById("overviewChart"), {
                type: "bar",
                data: {
                    labels: filtered.map(e => e.day),
                    datasets: [
                        { label: "Eventos", data: filtered.map(e => e.totalEvents), backgroundColor: 'grey' },
                        { label: "Intrusões", data: filtered.map(e => e.totalIntrusions), backgroundColor: 'red' },
                        { label: "Normais", data: filtered.map(e => e.totalNormal), backgroundColor: 'green' }
                    ]
                }
            });
        }
    }
};
</script>

<style scoped>
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
</style>