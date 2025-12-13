<template>
    <div class="container mt-4">
        <h3 class="mb-3 text-white">Categorias de Ameaças</h3>
        <div class="row">
            <div class="col-md-12"><canvas id="pieChart"></canvas></div>
        </div>
    </div>
</template>
  
<script>
    import { Chart } from "chart.js/auto";
  
    export default {
        props: ['labels', 'data'],
		data() {
			return {
				chart: null
			};
		},
        methods: {
			mountChart() {
				if (!this.chart) {
					this.chart = new Chart(document.getElementById("pieChart"), {
                        type: "pie",
                        data: { labels: this.labels, datasets: [{ data: this.data }] }
                    });
				} else {
					this.updateChart();
				}
			},
			updateChart() {
				this.chart.data.labels = this.labels;
				this.chart.data.datasets[0].data = this.data;
				this.chart.update();
			}
		},
		watch: {
        	data() {
				this.mountChart();
			},
		},
        mounted() {
            
        }
    };
</script>