<template>
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-12">
                <div class="card p-3 text-center">
                    <h5>{{ translations.today_events }}</h5>
                    <h2 class="highlight">{{ today }}</h2>
                </div>
            </div>
            <div class="col-md-3" v-for="item in classificationSummary" :key="item.description">
                <div class="card p-3 text-center">
                    <h5>{{ item.description }}</h5>
                    <h2 :class="`text-${item.visual_style}`">{{ item.count }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                translations: {}
            }
        },
        mounted() {
            utils.loadTranslations(this, 'event_summary_domain', 'translations');
        },
        props: ['events'],
        computed: {
            today() {
                return this.events.data ? this.events.data.length : 0;
            },
            classificationSummary() {
                const data = (this.events && this.events.data) ? this.events.data : [];
                const map = data.reduce((acc, ev) => {
                    const cls = ev.classification || {};
                    const desc = cls.description != undefined ? 'Risco ' + cls.description : 'Sem Risco Classificado';
                    const visual = (cls.visual_style || 'secondary').toString().replace(/^text-/, '');
                    if (!acc[desc]) {
                        acc[desc] = { description: desc, count: 0, visual_style: visual };
                    }
                    acc[desc].count += 1;
                    return acc;
                }, {});
            return Object.values(map).sort((a, b) => b.count - a.count);
        }
    },
};
</script>