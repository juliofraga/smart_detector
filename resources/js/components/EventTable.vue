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
                eventMetadata: [],
                baseTitle: {
                    description: { title: 'Descrição', hidden: 'false', type: 'text' },
                    ip_address: { title: 'IP Origem', hidden: 'false', type: 'text' },
                    type: { title: 'Tipo de Ameaça', hidden: 'false', type: 'text' },
                    classification: { title: 'Risco', hidden: 'false', type: 'badge' },
                    event_date_time: { title: 'Data/Hora', hidden: 'false', type: 'timestamp' },
                    id: { hidden: 'true' },
                    analysys: { hidden: 'true' },
                    geographical_origin: { hidden: 'true' },
                    request: { hidden: 'true' },
                    detalhes: {
                        title: 'Detalhes',
                        hidden: 'false',
                        type: 'buttonModal',
                        modalId: '#modalEventDetail',
                        buttonType: 'view'
                    }
                }
            }
        },
        methods: {
            getEventMetadata() {
                let url = this.urlBaseEventMetadata + '/show-enabled';
                utils.axiosGet(url, this, 'eventMetadata');
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
        }
    }
</script>
