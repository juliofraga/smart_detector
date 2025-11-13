<template>
    <div class="container">
        <search-component 
            title="LLM's" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdd'
                },
                search: {
                    show: true,
                    fields: ['name', 'provider', 'model_id']
                },
                clear: {
                    show: true,
                },
                searchDate: {
                    show: false
                }
            }" 
            placeholder="Buscar por nome, provider ou modelo"
            classSearch="llm"
        ></search-component>
        <div v-if="Object.keys(llms.data).length > 0">
            <table-component
                :title="{
                    id: {title: 'ID', hidden: 'true', type:'text'},
                    name: {title: 'Nome', hidden: 'false', type:'text'},           
                    provider: {title: 'Provider', hidden: 'false', type: 'text'},
                    model_id: {title: 'Modelo', hidden: 'false', type: 'text'},
                    notes: {title: 'Notas', hidden: 'false', type: 'text'},
                    api_base_url: {title: 'URL', hidden: 'true', type: 'text'},
                    api_key: {title: 'API Key', hidden: 'true', type: 'text'},
                    max_tokens: {title: 'Máximo de Tokens', hidden: 'true', type: 'text'},
                    default_temperature: {title: 'Temperatura', hidden: 'true', type: 'text'},
                    context_length: {title: 'Contexto', hidden: 'true', type: 'text'},
                    pricing_prompt_token: {title: 'Custo por 1k tokens de prompt', hidden: 'true', type: 'text'},
                    pricing_completion_token: {title: 'Custo por 1k tokens de resposta', hidden: 'true', type: 'text'},
                    active: {title: 'Ativo', hidden: 'true', type: 'yesno'},
                    created_at: {title: 'Criado em', hidden: 'true', type: 'timestamp'},
                    updated_at: {title: 'Atualizado em', hidden: 'true', type: 'timestamp'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                }" 
                :data="llms.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                sectionTitle="LLM's Cadastradas"
                classList="llm"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhuma LLM encontrada"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "llms"></paginate-component>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                llms: {data: {}},
                urlBase: utils.API_URL + '/api/v1/llm',
                urlPaginate: '',
                urlFilter: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                description: '',
                descriptionUpdate: '',
                loaded: false,
            }
        },
        methods: {
            save() {
                if (utils.fieldsValidate(['description'], this)) {
                    let data = {
                        description: this.description,
                    };
                    let url = this.urlBase;
                    utils.axiosPost(url, data, this);                        
                }
            },
            update() {
                utils.removeRequiredFieldMessage(['descriptionUpdate']);
                if (this.$store.state.item.description == ''){
                    utils.showRequiredFieldMessage('descriptionUpdate');
                } else {
                    utils.removeRequiredFieldMessage(['descriptionUpdate']);
                    let data = {
                        description: this.$store.state.item.description
                    };
                    let url = this.urlBase + '/' + this.$store.state.item.id;
                    utils.axiosPatch(url, data, this);
                }
            },
            deleteRecord() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                utils.axiosDelete(url, this);
            },
            loadList() {
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFilter;
                utils.axiosGet(url, this, 'llms');
                utils.cleanFeedbackMessage(this);                    
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            cleanAddFormData() {
                utils.cleanAddFormData(this, ['description']);
            },
            showModal(modal) {
                utils.showModal(modal);
            },
        },
        mounted() {
            EventBus.$on("loadList", this.loadList)
            EventBus.$on("setUrlFilter", this.setUrlFilter);
            EventBus.$on("paginate", this.paginate);
            EventBus.$on("deleteRecord", this.deleteRecord);
            EventBus.$on("update", this.update);
            EventBus.$on("save", this.save);
            this.loadList();
        }
    }
</script>

