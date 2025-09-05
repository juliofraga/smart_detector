<template>
    <div class="container">
        <div class="container-fluid p-4">
            <div class="mb-4">
                <h2 class="fw-bold">Painel de Monitoramento <span class="highlight">Smart Detector</span></h2>
                <p class="text-secondary">Visualize eventos em tempo real.</p>
            </div>
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
            <div class="card p-3">
                <h5 class="mb-3">Eventos Recentes</h5>
                <div class="table-responsive">
                    <table class="table table-dark-custom table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>IP Origem</th>
                                <th>Tipo</th>
                                <th>Classificação</th>
                                <th>Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                <td>192.168.0.15</td>
                                <td>Brute Force</td>
                                <td><span class="badge badge-danger">ALTO RISCO</span></td>
                                <td>2025-08-28 19:22</td>
                            </tr>
                            <tr>
                                <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                <td>201.55.23.87</td>
                                <td>SQL Injection</td>
                                <td><span class="badge badge-warning">MÉDIO RISCO</span></td>
                                <td>2025-08-28 19:22</td>
                            </tr>
                            <tr>
                                <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                <td>45.12.98.10</td>
                                <td>Acesso</td>
                                <td><span class="badge badge-warning">MÉDIO RISCO</span></td>
                                <td>2025-08-28 19:22</td>
                            </tr>
                            <tr>
                                <td>Lorem ipsum dolor sit amet consectetur adipiscing elit.</td>
                                <td>177.45.90.1</td>
                                <td>XSS</td>
                                <td><span class="badge badge-success">BAIXO RISCO</span></td>
                                <td>2025-08-28 19:22</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
