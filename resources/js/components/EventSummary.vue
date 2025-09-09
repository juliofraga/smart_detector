<template>
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Eventos Hoje</h5>
                    <h2 class="highlight">500</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Risco Alto</h5>
                    <h2 class="text-danger">215</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Risco Médio</h5>
                    <h2 class="text-warning">200</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <h5>Risco Baixo</h5>
                    <h2 class="text-success">85</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['events']
    }
</script>
