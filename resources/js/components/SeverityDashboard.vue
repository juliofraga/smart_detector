<template>
	<div class="container mt-4">
		<h3 class="mb-3 text-white">{{ translations.risk_classification }}</h3>
		<div class="col-md-12 mx-auto">
			<canvas id="donutChart"></canvas>
		</div>
		{{ labels }}
	</div>
</template>
  
<script>
  	import { Chart } from "chart.js/auto";
	import * as utils from '../utils/functions';
	export default {
		props: ['labels', 'data'],
		data() {
			return {
				chart: null,
				translations: {}
			};
		},
		mounted() {
			utils.loadTranslations(this, 'severity_dashboard_domain', 'translations');
		},
		methods: {
			mountChart() {
				if (!this.chart) {
					this.chart = new Chart(document.getElementById("donutChart"), {
						type: "doughnut",
						data: { labels: this.labels, datasets: [{ data: this.data, backgroundColor: this.setColors() }] }
					});
				} else {
					this.updateChart();
				}
			},
			updateChart() {
				this.chart.data.labels = this.labels;
				this.chart.data.datasets[0].data = this.data;
				this.chart.data.datasets[0].backgroundColor = this.setColors();
				this.chart.update();
			},
			setColors() {
				const colorMap = {
					"Muito Alto": "black",
					"Alto": "red",
					"Médio": "orange",
					"Baixo": "green",
					"Muito Baixo": "LightSkyBlue"
				};
				const colors = this.labels.map(label => colorMap[label] || "#999");
				return colors;
			}
		},
		watch: {
        	data() {
				this.mountChart();
			},
		},
		beforeUnmount() {
			if (this.chart) {
				this.chart.destroy();
			}
		}
	};
</script>