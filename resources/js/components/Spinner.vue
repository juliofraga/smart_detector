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
                const translations = {
                    pt_BR: 'Carregando',
                    es: 'Cargando',
                    fr: 'Chargement',
                    en: 'Loading'
                };
                this.translation = translations[locale] || translations.en;
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
