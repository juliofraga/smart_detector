<template>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-primary">404</h1>
            <h3>Página não encontrada</h3>
            <p class="text-muted">
                O recurso que você está tentando acessar não existe ou foi movido.
            </p>
            <a href="/home" class="btn btn-outline-primary">
                Voltar para o início
            </a>
        </div>
    </div>
</template>
        
<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
			return {
				translations: {},
                loaded: false
			};
		},
        methods: {
            loadtranslations() {

            }
        },
        mounted() {
            this.loadtranslations();
        }
    };
</script>
    
<style scoped>
    h1 {
        color: #4EA5FF;
    }
</style>