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
        mounted() {
            new Chart(document.getElementById("pieChart"), {
                type: "pie",
                data: { labels: ["SQLi", "XSS", "Traversal", "Brute Force"], datasets: [{ data: [40, 25, 20, 15] }] }
            });
        }
    };
</script>