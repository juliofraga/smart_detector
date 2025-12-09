<template>
    <div class="container mt-4">
        <h3 class="mb-3 text-white">Visão Geral</h3>
        <div class="row mt-4">
            <div class="col-md-12">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
    data() {
        return {
            startDate: "2025-01-01",
            endDate: "2025-01-07",

            mockEvents: [
                { date: "2025-01-01", eventos: 50, intrusions: 15, normal: 2 },
                { date: "2025-01-02", eventos: 80, intrusions: 20, normal: 3 },
                { date: "2025-01-03", eventos: 60, intrusions: 18, normal: 1 },
                { date: "2025-01-04", eventos: 100, intrusions: 25, normal: 4 },
                { date: "2025-01-05", eventos: 90, intrusions: 30, normal: 5 },
                { date: "2025-01-06", eventos: 70, intrusions: 22, normal: 3 },
                { date: "2025-01-07", eventos: 110, intrusions: 35, normal: 6 }
            ],

            totals: {
                totalEventos: 0,
                totalIntrusions: 0,
                totalNormal: 0
            },
            chart: null
        };
    },
    mounted() {
        this.applyRange();
    },
    methods: {
        applyRange() {
            const filtered = this.mockEvents.filter(e => e.date >= this.startDate && e.date <= this.endDate);

            const totals = {
                totalEventos: filtered.reduce((t, e) => t + e.eventos, 0),
                totalIntrusions: filtered.reduce((t, e) => t + e.intrusions, 0),
                totalNormal: filtered.reduce((t, e) => t + e.normal, 0),
            };

            this.totals = totals;
            this.renderChart(filtered);
        },
        renderChart(filtered) {
            if (this.chart) this.chart.destroy();
            this.chart = new Chart(document.getElementById("overviewChart"), {
                type: "bar",
                data: {
                    labels: filtered.map(e => e.date),
                    datasets: [
                        { label: "Eventos", data: filtered.map(e => e.eventos), backgroundColor: 'grey' },
                        { label: "Intrusões", data: filtered.map(e => e.intrusions), backgroundColor: 'red' },
                        { label: "Normais", data: filtered.map(e => e.normal), backgroundColor: 'green' }
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