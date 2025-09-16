<template>
    <div class="container">
        <search-component 
            title="Tipos de Ameaças" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdicionarTipo'
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
            classSearch="type"
        ></search-component>
        <div v-if="Object.keys(types.data).length > 0">
            <table-component
                :title="{
                    id: {title: 'ID', hidden: 'true', type:'text'},
                    description: {title: 'Descrição', hidden: 'false', type:'text'},           
                    created_at: {title: 'Data de Criação', hidden: 'false', type: 'timestamp'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalAtualizarTipo', buttonType: 'edit'},
                }" 
                :data="types.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                sectionTitle="Tipos de Ameaças Cadastradas"
                classList="type"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhum tipo de ameaça encontrada"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <div class="row mt-4" v-if="types.data.length > 0">
            <div class="col col-10">
                <paginate-component>
                    <li v-for="l, key in types.links" :key="key" class="page-item" @click="paginate(l)">
                        <div v-if="l.active">
                            <a class="page-link dark-paginantion-active" v-html="l.label" 
                            v-if="
                                key == types.current_page || 
                                key == types.current_page - 1 || 
                                key == types.current_page + 1 || 
                                key == 0 ||
                                (types.current_page == 1 && key == 3) ||
                                key == types.last_page + 1 || 
                                (types.current_page == types.last_page && key == types.last_page - 2)"
                        ></a>
                        </div>
                        <div v-else>
                            <a class="page-link dark-pagination" 
                            v-if="
                                l.url != null && 
                                (key == types.current_page || 
                                key == types.current_page - 1 || 
                                key == types.current_page + 1 || 
                                key == 0 ||
                                (types.current_page == 1 && key == 3) ||
                                key == types.last_page + 1 || 
                                (types.current_page == types.last_page && key == types.last_page - 2))"
                        >{{ l.label | formatNextPrevButton }}</a>
                        </div>
                        
                    </li>
                </paginate-component>
            </div>
        </div>
        <!-- Modal para adicionar tipo de ameaça -->
        <modal-component id="modalAdicionarTipo" title="Adicionar Tipo de Ameaça">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="description" name="description" placeholder="Descrição*" v-model="description">
                                <label class="form-label">Descrição*</label>
                                <div id="invalidFeedbackDescricao" class="invalid-feedback">
                                    Informe a descrição.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success text-white" @click="save()">Salvar</button>
            </template>
        </modal-component>
        <!-- Modal para atualizar tipo de ameaça -->
        <modal-component id="modalAtualizarTipo" title="Atualizar Tipo de Ameaça">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="descriptionUpdate" name="descriptionUpdate" placeholder="Descrição*" v-model="$store.state.item.description">
                                <label class="form-label">Descrição*</label>
                                <div id="invalidFeedbackDescricaoUpdate" class="invalid-feedback">
                                    Informe a descrição.
                                </div>
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
                <button type="button" class="btn btn-success text-white" @click="update()">Atualizar</button>
                <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#modalConfirmarDeletar">Deletar</button> 
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> 
            </template>
        </modal-component>
        <!-- Modal para confirmar remoção de tipo de ameaça -->
        <modal-component id="modalConfirmarDeletar" options="modal-dialog-centered modal-sm" title="Você tem certeza?">
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col col-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" @click="showModal('modalAtualizarTipo')">Não</button>
                    </div>
                    <div class="col col-6">
                        <button type="button" class="btn btn-danger text-white w-100" @click="deleteTipoAmeaca()">Sim</button>
                    </div>
                </div>
            </template>
            <template v-slot:rodape></template>
        </modal-component>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                types: {data: {}},
                urlBase: utils.API_URL + '/api/v1/type',
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
                    axios.post(url, data)
                        .then(response => {
                            this.status = 'success';
                            this.feedbackTitle = "Tipo de ameaça adicionada com sucesso";
                            utils.closeModal('modalAdicionarTipo');
                            this.loadTipoAmeacaList();
                            this.cleanAddTipoFormData();
                        })
                        .catch(errors => {
                            this.status = 'error';
                            this.feedbackTitle = "Erro ao adicionar tipo de ameaça";
                            utils.closeModal('modalAdicionarTipo');
                            this.feedbackMessage = {
                                mensagem: errors.response.data.message,
                                data: errors.response.data.errors
                            };
                            this.cleanFeedbackMessage();
                        })
                        
                }
            },
            update() {
                if (this.$store.state.item.description == ''){
                    document.getElementById('descriptionUpdate').classList.add('is-invalid');
                } else {
                    if (document.getElementById('descriptionUpdate').classList.contains('is-invalid')) {
                        document.getElementById('descriptionUpdate').classList.remove('is-invalid');
                    }
                    let data = {
                        description: this.$store.state.item.description
                    };
                    let url = this.urlBase + '/' + this.$store.state.item.id;
                    axios.patch(url, data)
                        .then(response => {
                            this.status = 'success';
                            this.feedbackTitle = "Tipo de ameaça atualizada com sucesso";
                            utils.closeModal('modalAtualizarTipo');
                            this.loadTipoAmeacaList();
                        })
                        .catch(errors => {
                            this.status = 'error';
                            this.feedbackTitle = "Erro ao atualizar tipo de ameaça";
                            utils.closeModal('modalAtualizarTipo');
                            this.feedbackMessage = {
                                message: errors.response.data.message,
                                data: errors.response.data.errors
                            };
                        })
                }
            },
            deleteTipoAmeaca() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                axios.delete(url)
                    .then(response => {
                        this.status = 'success';
                        this.feedbackTitle = "Tipo de ameaça deletada com sucesso";
                        utils.closeModal('modalConfirmarDeletar');
                        this.loadTipoAmeacaList();
                    })
                    .catch(errors => {
                        this.status = 'error';
                        this.feedbackTitle = "Erro ao deletar tipo de ameaça";
                        utils.closeModal('modalConfirmarDeletar');
                        this.feedbackMessage = {
                            message: errors.response.data.message,
                            data: errors.response.data.errors
                        };
                    })
            },
            loadTipoAmeacaList() {
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFilter;
                axios.get(url)
                    .then(response => {
                        this.types = response.data;
                        this.loaded = true;
                    })
                    .catch(errors => {
                        if (errors.response.status == 500) {
                            this.feedbackTitle = "Erro no servidor";
                            this.status = 'error';
                            this.feedbackMessage = {message: "Desculpe, não conseguimos processar a sua requisição, tente novamente ou entre em contato com a equipe de suporte"}
                        } else {
                            this.feedbackTitle = "Houve um erro";
                            this.status = 'error';
                            this.feedbackMessage = errors;
                        }
                    })
                this.cleanFeedbackMessage();                    
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            cleanFeedbackMessage() {
                setTimeout(() => {
                    this.feedbackMessage =  {};
                    this.feedbackTitle = '';
                    this.status = '';
                }, 10000);
            },
            cleanAddTipoFormData() {
                this.description = '';
            },
            showModal(modal) {
                utils.showModal(modal);
            },
        },
        mounted() {
            EventBus.$on("loadTipoAmeacaList", this.loadTipoAmeacaList)
            EventBus.$on("setUrlFilter", this.setUrlFilter);
            this.loadTipoAmeacaList();
        }
    }
</script>
