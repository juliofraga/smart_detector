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
        props:['data', 'title'],
        data() {
            return {
                urlBaseEventMetadata: utils.API_URL + '/api/v1/event-attribute',
                urlBaseSettings: utils.API_URL + '/api/v1/system-settings',
                eventMetadata: [],
                baseTitle: {
                    description: { title: 'Descrição', hidden: 'false', type: 'text' },
                    ip_address: { title: 'IP Origem', hidden: 'false', type: 'text' },
                    type: { title: 'Tipo de Ameaça', hidden: 'false', type: 'text' },
                    classification: { title: 'Risco', hidden: 'false', type: 'badge' },
                    event_date_time: { title: 'Data/Hora', hidden: 'false', type: 'timestamp' },
                    id: { hidden: 'true' },
                    analysys: { hidden: 'true' },
                    detalhes: {
                        title: 'Detalhes',
                        hidden: 'false',
                        type: 'buttonModal',
                        modalId: '#modalEventDetail',
                        buttonType: 'view'
                    }
                },
                settings: {data: {}},
                allEvents: false,
            }
        },
        methods: {
            getEventMetadata() {
                let url = this.urlBaseEventMetadata + '/show-enabled';
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
                const allEvents = newVal.find(item => item.attribute === 'all_events');
                    if (allEvents) {
                        if (allEvents.value == 'Yes') {
                            this.allEvents = true;
                            const newBase = {};
                            for (const key in this.baseTitle) {
                                newBase[key] = this.baseTitle[key];

                                if (key === 'classification') {
                                    newBase['intrusion_normal'] = {
                                        title: "Classificação",
                                        hidden: 'false',
                                        type: 'badge'
                                    };
                                }
                            }
                            this.baseTitle = newBase;
                        } else {
                            this.allEvents = false;
                            this.$delete(this.baseTitle, 'intrusion_normal');
                        }
                    } else {
                        this.allEvents = false;
                    }
                }
            }
        },
        computed: {
            mergedTitle() {
                const dynamicTitles = this.eventMetadata.reduce((acc, meta) => {
                acc[meta.field_name] = {
                    title: meta.display_value,
                    hidden: 'true',
                    type: meta.type_field
                };
                return acc;
                }, {});

                return { ...this.baseTitle, ...dynamicTitles };
            }
        },
        mounted() {
            this.getEventMetadata();
            this.loadSettings();
        }
    }
</script>
