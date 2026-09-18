<template>
    <div class="container">
        <table-component
            :title="mergedTitle"
            :data="data"
            :sectionTitle="title"
            classList="event"
        ></table-component>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        props:['data', 'title', 'locale'],
        data() {
            return {
                urlBaseEventMetadata: utils.API_URL + '/api/v1/event-attribute',
                urlBaseSettings: utils.API_URL + '/api/v1/system-settings',
                eventMetadata: [],
                settings: { data: {} },
                allEvents: false,
            }
        },
        methods: {
            getEventMetadata() {
                let url = this.urlBaseEventMetadata + '/table-columns';
                utils.axiosGet(url, this, 'eventMetadata');
            },
            loadSettings() {
                let url = this.urlBaseSettings;
                utils.axiosGet(url, this, 'settings');
            }
        },
        watch: {
            settings(newVal) {
                if (Array.isArray(newVal) && newVal.length > 0) {
                    const allEvents = newVal.find(
                        item => item.attribute === 'all_events'
                    );

                    this.allEvents = allEvents?.value === 'Yes';
                } else {
                    this.allEvents = false;
                }
            }
        },
        computed: {
            mergedTitle() {
                const dynamicTitles = this.eventMetadata.reduce((acc, meta) => {
                    acc[meta.field_name] = {
                        title: meta.display_value,
                        hidden: 'false',
                        type: meta.type_field
                    };
                    return acc;
                }, {});

                // Monta na ordem: colunas base sem classification/detalhes
                // → colunas dinâmicas → classification (condicional) → detalhes
                const ordered = { ...this.baseTitle };
                // Remove classification e detalhes do lugar original
                const { classification, detalhes, ...baseWithout } = ordered;

                const result = { ...baseWithout, ...dynamicTitles };

                if (this.allEvents) {
                    const t = this.currentTranslations;
                    result.intrusion_normal = {
                        title: t.intrusion_normal,
                        hidden: 'false',
                        type: 'badge'
                    };
                }

                if (classification) {
                    result.classification = classification;
                }
                if (detalhes) {
                    result.detalhes = detalhes;
                }

                return result;
            },
            currentTranslations() {
                const translations = {
                    pt_BR: {
                        description: 'Descrição',
                        ip_address: 'IP Origem',
                        type: 'Tipo de Ameaça',
                        classification: 'Risco',
                        intrusion_normal: 'Classificação',
                        ids_agent: 'IDS Origem',
                        event_date_time: 'Data/Hora',
                        details: 'Detalhes'
                    },
                    en: {
                        description: 'Description',
                        ip_address: 'Source IP',
                        type: 'Threat Type',
                        classification: 'Risk',
                        intrusion_normal: 'Classification',
                        ids_agent: 'Source IDS',
                        event_date_time: 'Date/Time',
                        details: 'Details'
                    },
                    es: {
                        description: 'Descripción',
                        ip_address: 'IP de Origen',
                        type: 'Tipo de Amenaza',
                        classification: 'Riesgo',
                        intrusion_normal: 'Clasificación',
                        ids_agent: 'IDS de Origen',
                        event_date_time: 'Fecha/Hora',
                        details: 'Detalles'
                    },
                    fr: {
                        description: 'Description',
                        ip_address: 'IP Source',
                        type: 'Type de Menace',
                        classification: 'Risque',
                        intrusion_normal: 'Classification',
                        ids_agent: 'IDS Source',
                        event_date_time: 'Date/Heure',
                        details: 'Détails'
                    }
                };
                return translations[this.locale] || translations.pt_BR;
            },
            baseTitle() {
                const t = this.currentTranslations;

                return {
                    description: { title: t.description, hidden: 'false', type: 'text' },
                    ip_address: { title: t.ip_address, hidden: 'false', type: 'text' },
                    type: { title: t.type, hidden: 'false', type: 'text' },
                    classification: { title: t.classification, hidden: 'false', type: 'badge' },
                    ids_agent: { title: t.ids_agent, hidden: 'false', type: 'text_object' },
                    event_date_time: { title: t.event_date_time, hidden: 'false', type: 'datetime' },
                    id: { hidden: 'true' },
                    analysys: { hidden: 'true' },
                    detalhes: {
                        title: t.details,
                        hidden: 'false',
                        type: 'buttonModal',
                        modalId: '#modalEventDetail',
                        buttonType: 'view'
                    }
                };
            }
        },
        mounted() {
            this.getEventMetadata();
            this.loadSettings();
        }
    }
</script>
