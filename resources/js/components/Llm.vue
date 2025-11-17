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
        <!-- Modal para adicionar LLM's -->
        <modal-component id="modalAdd" options="modal-dialog-centered modal-md" title="Adicionar LLM">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome (ex: 'GPT-5', 'Claude 3.5', 'Gemini 1.5')*" v-model="name">
                                <label class="form-label">Nome (ex: 'GPT-5', 'Claude 3.5', 'Gemini 1.5')*</label>
                                <div id="invalidFeedbackName" class="invalid-feedback">
                                    Informe o nome.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="provider" name="provider" placeholder="Provider (ex: 'OpenAI', 'Google')*" v-model="provider">
                                <label class="form-label">Provider (ex: 'OpenAI', 'Google')*</label>
                                <div id="invalidFeedbackProvider" class="invalid-feedback">
                                    Informe a provider.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="model_id" name="model_id" placeholder="Model ID (ex: gpt-5, gemini-1.5-pro)*" v-model="model_id">
                                <label class="form-label">Model ID (ex: gpt-5, gemini-1.5-pro)*</label>
                                <div id="invalidFeedbackModelId" class="invalid-feedback">
                                    Informe o Model ID.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="api_base_url" name="api_base_url" placeholder="API URL (ex: https://api.openai.com/v1)*" v-model="api_base_url">
                                <label class="form-label">API URL (ex: https://api.openai.com/v1)*</label>
                                <div id="invalidFeedbackApiBaseUrl" class="invalid-feedback">
                                    Informe a URL da API.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="api_key" name="api_key" placeholder="API Key*" v-model="api_key">
                                <label class="form-label">API Key*</label>
                                <div id="invalidFeedbackApiKey" class="invalid-feedback">
                                    Informe a API Key.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pricing_prompt_token" name="pricing_prompt_token" placeholder="Custo por 1k tokens de prompt" v-model="pricing_prompt_token" @input="maskMoney('pricing_prompt_token')">
                                <label class="form-label">Custo por 1k tokens de prompt</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pricing_completion_token" name="pricing_completion_token" placeholder="Custo por 1k tokens de resposta" v-model="pricing_completion_token" @input="maskMoney('pricing_completion_token')">
                                <label class="form-label">Custo por 1k tokens de resposta</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="max_tokens" name="max_tokens" placeholder="Limite de tokens permitido por requisição" v-model="max_tokens">
                                <label class="form-label">Limite de tokens permitido por requisição</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="default_temperature" name="default_temperature" placeholder="Temperatura Padrão*" v-model="default_temperature">
                                <label class="form-label">Temperatura Padrão*</label>
                                <div id="invalidFeedbackDefaultTemperature" class="invalid-feedback">
                                    Informe a temperatura padrão.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="context_length" name="context_length" placeholder="Quantos tokens o modelo aceita no contexto?" v-model="context_length">
                                <label class="form-label">Quantos tokens o modelo aceita no contexto?</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <textarea class="form-control rounded border-0" rows="10" id="notes" name="notes" style="height: auto;" v-model="notes"></textarea>
                                <label class="form-label">Notas*</label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <add-cancel-buttons-component></add-cancel-buttons-component>
            </template>
        </modal-component>
        <!-- Modal para atualizar LLM's -->
        <modal-component id="modalUpdate" options="modal-dialog-centered modal-md" title="Atualizar LLM">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nameUpdate" name="nameUpdate" placeholder="Nome (ex: 'GPT-5', 'Claude 3.5', 'Gemini 1.5')*" v-model="$store.state.item.name">
                                <label class="form-label">Nome (ex: 'GPT-5', 'Claude 3.5', 'Gemini 1.5')*</label>
                                <div id="invalidFeedbackName" class="invalid-feedback">
                                    Informe o nome.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="providerUpdate" name="providerUpdate" placeholder="Provider (ex: 'OpenAI', 'Google')*" v-model="$store.state.item.provider">
                                <label class="form-label">Provider (ex: 'OpenAI', 'Google')*</label>
                                <div id="invalidFeedbackProvider" class="invalid-feedback">
                                    Informe a provider.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="model_idUpdate" name="model_idUpdate" placeholder="Model ID (ex: gpt-5, gemini-1.5-pro)*" v-model="$store.state.item.model_id">
                                <label class="form-label">Model ID (ex: gpt-5, gemini-1.5-pro)*</label>
                                <div id="invalidFeedbackModelId" class="invalid-feedback">
                                    Informe o Model ID.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="api_base_urlUpdate" name="api_base_urlUpdate" placeholder="API URL (ex: https://api.openai.com/v1)*" v-model="$store.state.item.api_base_url">
                                <label class="form-label">API URL (ex: https://api.openai.com/v1)*</label>
                                <div id="invalidFeedbackApiBaseUrl" class="invalid-feedback">
                                    Informe a URL da API.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="api_keyUpdate" name="api_keyUpdate" placeholder="API Key*" v-model="$store.state.item.api_key">
                                <label class="form-label">API Key*</label>
                                <div id="invalidFeedbackApiKey" class="invalid-feedback">
                                    Informe a API Key.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pricing_prompt_tokenUpdate" name="pricing_prompt_tokenUpdate" placeholder="Custo por 1k tokens de prompt" v-model="$store.state.item.pricing_prompt_token">
                                <label class="form-label">Custo por 1k tokens de prompt</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pricing_completion_tokenUpdate" name="pricing_completion_tokenUpdate" placeholder="Custo por 1k tokens de resposta" v-model="$store.state.item.pricing_completion_token">
                                <label class="form-label">Custo por 1k tokens de resposta</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="max_tokensUpdate" name="max_tokensUpdate" placeholder="Limite de tokens permitido por requisição" v-model="$store.state.item.max_tokens">
                                <label class="form-label">Limite de tokens permitido por requisição</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="default_temperatureUpdate" name="default_temperatureUpdate" placeholder="Temperatura Padrão*" v-model="$store.state.item.default_temperature">
                                <label class="form-label">Temperatura Padrão*</label>
                                <div id="invalidFeedbackDefaultTemperature" class="invalid-feedback">
                                    Informe a temperatura padrão.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="context_lengthUpdate" name="context_lengthUpdate" placeholder="Quantos tokens o modelo aceita no contexto?" v-model="$store.state.item.context_length">
                                <label class="form-label">Quantos tokens o modelo aceita no contexto?</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <textarea class="form-control rounded border-0" rows="10" id="notesUpdate" name="notesUpdate" style="height: auto;" v-model="$store.state.item.notes"></textarea>
                                <label class="form-label">Notas*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label class="form-label text-light"><i>Data de criação: {{ $store.state.item.created_at | formatDateTimeStamp}}</i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label text-light"><i>Última atualização: {{ $store.state.item.updated_at | formatDateTimeStamp}}</i></label>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <updates-button-component></updates-button-component> 
            </template>
        </modal-component>
        <!-- Modal para confirmar remoção de tipo de ameaça -->
        <modal-delete-component></modal-delete-component>
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
                name: '',
                provider: '',
                model_id: '',
                api_base_url: '',
                api_key: '',
                max_tokens: '',
                default_temperature: '',
                context_length: '',
                pricing_prompt_token: '',
                pricing_completion_token: '',
                notes: '',
                nameUpdate: '',
                providerUpdate: '',
                model_idUpdate: '',
                api_base_urlUpdate: '',
                api_keyUpdate: '',
                max_tokensUpdate: '',
                default_temperatureUpdate: '',
                context_lengthUpdate: '',
                pricing_prompt_tokenUpdate: '',
                pricing_completion_tokenUpdate: '',
                notesUpdate: '',
                loaded: false,
            }
        },
        methods: {
            save() {
                if (utils.fieldsValidate(['name', 'provider', 'model_id', 'api_base_url', 'api_key', 'default_temperature'], this)) {
                    let data = {
                        name: this.name,
                        provider: this.provider,
                        model_id: this.model_id,
                        api_base_url: this.api_base_url,
                        api_key: this.api_key,
                        max_tokens: this.max_tokens,
                        default_temperature: this.default_temperature,
                        context_length: this.context_length,
                        pricing_prompt_token: this.pricing_prompt_token,
                        pricing_completion_token: this.pricing_completion_token,
                        notes: this.notes
                    };
                    let url = this.urlBase;
                    utils.axiosPost(url, data, this);                        
                }
            },
            update() {
                utils.removeRequiredFieldMessage(['nameUpdate', 'providerUpdate', 'model_idUpdate', 'api_base_urlUpdate', 'api_keyUpdate', 'default_temperatureUpdate']);
                if (this.$store.state.item.name == ''){
                    utils.showRequiredFieldMessage('nameUpdate');
                } else if(this.$store.state.item.provider == '') {
                    utils.showRequiredFieldMessage('providerUpdate');
                } else if(this.$store.state.item.model_id == '') {
                    utils.showRequiredFieldMessage('model_idUpdate');
                } else if(this.$store.state.item.api_base_url == '') {
                    utils.showRequiredFieldMessage('api_base_urlUpdate');
                } else if(this.$store.state.item.api_key == '') {
                    utils.showRequiredFieldMessage('api_keyUpdate');
                } else if(this.$store.state.item.default_temperature == '') {
                    utils.showRequiredFieldMessage('default_temperatureUpdate');
                } else {
                    utils.removeRequiredFieldMessage(['nameUpdate', 'providerUpdate', 'model_idUpdate', 'api_base_urlUpdate', 'api_keyUpdate', 'default_temperatureUpdate']);
                    let data = {
                        name: this.$store.state.item.name,
                        provider: this.$store.state.item.provider,
                        model_id: this.$store.state.item.model_id,
                        api_base_url: this.$store.state.item.api_base_url,
                        api_key: this.$store.state.item.api_key,
                        max_tokens: this.$store.state.item.max_tokens,
                        default_temperature: this.$store.state.item.default_temperature,
                        context_length: this.$store.state.item.context_length,
                        pricing_prompt_token: this.$store.state.item.pricing_prompt_token,
                        pricing_completion_token: this.$store.state.item.pricing_completion_token,
                        notes: this.$store.state.item.notes
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
                utils.cleanAddFormData(this, ['name', 'provider', 'model_id', 'api_base_url', 'api_key', 'max_tokens','default_temperature', 'context_length', 'pricing_prompt_token', 'pricing_completion_token', 'notes']);
            },
            showModal(modal) {
                utils.showModal(modal);
            },
            maskMoney(field) {
                let value = this[field];
                value = value.replace(/\D/g, "");
                value = (Number(value) / 100).toFixed(2);
                value = value
                    .toString()
                    .replace(".", ",")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                this[field] = "R$ " + value;
            }
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

