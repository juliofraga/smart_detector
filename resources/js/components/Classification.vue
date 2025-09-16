<template>
    <div class="container">
        <search-component 
            title="Classificações de Risco" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdicionarClassificacao'
                },
                search: {
                    show: true,
                    fields: ['description']
                },
                clear: {
                    show: true,
                }
            }" 
            placeholder="Buscar por descrição"
            classSearch="classification"
        ></search-component>
        <div v-if="Object.keys(classifications.data).length > 0">
            <table-component
                :title="{
                    id: {title: 'ID', hidden: 'true', type:'text'},
                    description: {title: 'Descrição', hidden: 'false', type:'text'},
                    visual_style: {title: 'Estilo', hidden: 'false', type:'text'},               
                    created_at: {title: 'Data de Criação', hidden: 'true', type: 'datetime'},
                }" 
                :data="classifications.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                sectionTitle="Classificações de Risco Cadastradas"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhuma classificação de risco encontrada"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                classifications: {data: {}},
                urlBase: utils.API_URL + '/api/v1/classification',
                urlPaginate: '',
                urlFilter: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                description: '',
                visualStyle: '',
                descriptionUpdate: '',
                visualStyleUpdate: '',
                loaded: false,
            }
        },
        methods: {
        },
        mounted() {
        }
    }
</script>
