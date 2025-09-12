<template>
    <div class="container">
        <table-component
            :title="{
                description: {title: 'Descrição', hidden: 'false', type:'text'},
                ip_address: {title: 'IP Origem', hidden: 'false', type:'text'},
                type: {title: 'Tipo', hidden: 'false', type:'text'},               
                threat_classification: {title: 'Classificação', hidden: 'false', type:'text-badge-classification'},
                event_date_time: {title: 'Data/Hora', hidden: 'false', type:'timestamp'},
                id: {hidden: 'true'},
                ai_analysys: {hidden: 'true'},
                geographical_origin: {hidden: 'true'},
                request: {hidden: 'true'},
                detalhes: {title: 'Detalhes', hidden: 'false', type: 'buttonModal', modalId: '#modalEventDetail', buttonType: 'view'}
            }" 
            :data="data"
            :sectionTitle="title"
        ></table-component>
    </div>
</template>

<script>
    export default {
        props:['data', 'title']
    }
</script>
