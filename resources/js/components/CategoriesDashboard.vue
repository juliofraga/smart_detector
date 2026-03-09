<template>
    <div class="container mt-4">
        <h3 class="mb-3 text-white">{{ translations.threat_categories }}</h3>
        <div class="row">
            <div class="col-md-12"><canvas ref="pieChart"></canvas></div>
        </div>
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
				translations: {},
                loaded: false
			};
		},
        methods: {
			mountChart() {
				if (!this.chart) {
					const colors = this.labels.map((_, i) => {
						const hue = (i * 360 / this.labels.length);
						return `hsl(${hue}, 70%, 50%)`;
					});
					this.chart = new Chart(this.$refs.pieChart, {
                        type: "pie",
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
				const total = this.labels.length;

				return this.labels.map((_, index) => {
					const hue = (index * 360) / total;
					return `hsl(${hue}, 70%, 55%)`;
				});
			}
		},
		watch: {
        	data() {
				this.mountChart();
			},
		},
        mounted() {
            utils.loadTranslations(this, 'categories_dashboard_domain', 'translations');
        },
		beforeUnmount() {
			if (this.chart) {
				this.chart.destroy();
			}
		}
    };
</script>