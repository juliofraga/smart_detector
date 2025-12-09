<template>
	<div class="container mt-4">
		<h3 class="mb-3 text-white">Dashboard de Severidade</h3>
		<div class="col-md-12 mx-auto">
			<canvas id="donutChart"></canvas>
		</div>
	</div>
</template>
  
<script>
  	import { Chart } from "chart.js/auto";
  
	export default {
		mounted() {
			new Chart(document.getElementById("donutChart"), {
				type: "doughnut",
				data: { labels: ["Alto", "Médio", "Baixo"], datasets: [{ data: [30, 90, 180] }] }
			});
		}
	};
</script>