<template>
    <div class="container">
        <div class="container-fluid p-4">
            <div class="mb-4">
                <h2 class="fw-bold">{{ translations.monitoring_panel }} <span class="highlight">{{ translations.smart_detector }}</span></h2>
                <p class="text-secondary">{{ translations.view_events_real_time }}</p>
            </div>
            <div v-if="Object.keys(events.data).length > 0">
                <event-summary-component :events="events"></event-summary-component>
                <event-table-component :data="events.data" :title=translations.recent_events :locale="locale"></event-table-component>
            </div>
            <div v-else-if="loaded === true">
                <no-itens-component :message="translations.no_events_today"></no-itens-component>
            </div>
            <div v-else-if="loaded === false">
                <spinner-component></spinner-component>
            </div>
        </div>
        <event-modal-component></event-modal-component>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        props: ['translations', 'locale'],
        data() {
            return {
                events: {data: {}},
                urlBase: utils.API_URL + '/api/v1/event',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false,
                lastId: 0,
            }
        },
        methods: {
            loadList() {
                let url = this.urlBase;
                axios.get(url)
                    .then(response => {
                        this.events = response;
                        if (response.data.length > 0) {
                            this.lastId = Math.max(...this.events.data.map(e => e.id));
                        }
                        this.loaded = true;
                    })
                    .catch(errors => {
                        this.feedbackTitle = this.translations.an_error_happened;
                        this.status = 'error';
                        this.feedbackMessage = errors;
                    })                  
            },
            showNewEvent(eventData) {
                const merged = [...this.events.data, eventData]
                    .reduce((acc, current) => {
                        if (!acc.some(item => item.id === current.id)) {
                            acc.push(current);
                        }
                            return acc;
                        }, [])
                    .sort((a, b) => new Date(b.event_date_time) - new Date(a.event_date_time));
                this.events = { data: merged };
            }
        },
        mounted() {
            this.loadList();
            window.Echo.private('events')
                .listen('.EventCreated', (e) => {
                    this.showNewEvent(e.eventData);
                });
        }
    }
</script>
