<template>
    <div class="container">
        <div class="spinner-center">
            <div class="spinner"></div>
            <label style="margin-top:1%;" class="text-white">{{ translation }}...</label>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
			return {
				translation: 'Loading'
			};
		},
        methods: {
            setText(locale) {
                if (locale == 'pt_BR') {
                    this.translation = 'Carregando';
                } else if (locale == 'es') {
                    this.translation = 'Cargando';
                } else if (locale == 'fr') {
                    this.translation = 'Chargement';
                } else {
                    this.translation = 'Loading';
                }
            }
        },
        mounted() {
            const locale = document
                .querySelector('meta[name="app-locale"]')
                .getAttribute('content');
            this.setText(locale);
        }
    }
</script>
