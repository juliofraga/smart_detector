<template>
    <div class="container">
        <search-component 
            title="Classificações de Risco" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdd'
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
                    visual_style: {title: 'Estilo', hidden: 'false', type:'badge'},               
                    created_at: {title: 'Data de Criação', hidden: 'false', type: 'timestamp'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                }" 
                :data="classifications.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                sectionTitle="Classificações de Risco Cadastradas"
                classList="classification"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhuma classificação de risco encontrada"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "classifications"></paginate-component>
        <!-- Modal para adicionar classificações de risco -->
        <modal-component id="modalAdd" title="Adicionar Classificação de Risco">
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
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="visualStyle" name="visualStyle" placeholder="Estilo*" v-model="visualStyle" style="background-color: white;">
                                    <option value="">Selecione...</option>
                                    <option value="primary" class="bg-primary text-white">Primary</option>
                                    <option value="secondary" class="bg-secondary text-white">Secondary</option>
                                    <option value="success" class="bg-success text-white">Success</option>
                                    <option value="danger" class="bg-danger text-white">Danger</option>
                                    <option value="warning" class="bg-warning">Warning</option>
                                    <option value="info" class="bg-info">Info</option>
                                    <option value="light" class="bg-light">Light</option>
                                    <option value="dark" class="bg-dark text-white" style="color:">Dark</option>
                                </select>
                                <label class="form-label">Estilo*</label>
                                <div id="invalidFeedbackEstilo" class="invalid-feedback">
                                    Informe o estilo do alerta
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
        <!-- Modal para atualizar classificações de risco -->
        <modal-component id="modalUpdate" title="Atualizar Classificação de Risco">
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
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="visualStyleUpdate" name="visualStyleUpdate" placeholder="Estilo*" v-model="$store.state.item.visual_style" style="background-color: white;">
                                    <option value="">Selecione...</option>
                                    <option value="primary" class="bg-primary text-white">Primary</option>
                                    <option value="secondary" class="bg-secondary text-white">Secondary</option>
                                    <option value="success" class="bg-success text-white">Success</option>
                                    <option value="danger" class="bg-danger text-white">Danger</option>
                                    <option value="warning" class="bg-warning">Warning</option>
                                    <option value="info" class="bg-info">Info</option>
                                    <option value="light" class="bg-light">Light</option>
                                    <option value="dark" class="bg-dark text-white" style="color:">Dark</option>
                                </select>
                                <label class="form-label">Estilo*</label>
                                <div id="invalidFeedbackEstiloUpdate" class="invalid-feedback">
                                    Informe o estilo do alerta
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
                <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#modalConfirmDelete">Deletar</button> 
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> 
            </template>
        </modal-component>
        <!-- Modal para confirmar remoção de classificação de risco -->
        <modal-component id="modalConfirmDelete" options="modal-dialog-centered modal-sm" title="Você tem certeza?">
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col col-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" @click="showModal('modalUpdate')">Não</button>
                    </div>
                    <div class="col col-6">
                        <button type="button" class="btn btn-danger text-white w-100" @click="deleteClassification()">Sim</button>
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
            save() {
                if (utils.fieldsValidate(['description', 'visualStyle'], this)) {
                    let data = {
                        description: this.description,
                        visual_style: this.visualStyle,
                    };
                    let url = this.urlBase;
                    utils.axiosPost(url, data, this);                        
                }
            },
            update() {
                if (this.$store.state.item.description == ''){
                    document.getElementById('descriptionUpdate').classList.add('is-invalid');
                } else if (this.$store.state.item.visual_style == '') {
                    document.getElementById('visualStyleUpdate').classList.add('is-invalid');
                } else {
                    if (document.getElementById('descriptionUpdate').classList.contains('is-invalid')) {
                        document.getElementById('descriptionUpdate').classList.remove('is-invalid');
                    }
                    if (document.getElementById('visualStyleUpdate').classList.contains('is-invalid')) {
                        document.getElementById('visualStyleUpdate').classList.remove('is-invalid');
                    }
                    let data = {
                        description: this.$store.state.item.description,
                        visual_style: this.$store.state.item.visual_style
                    };
                    let url = this.urlBase + '/' + this.$store.state.item.id;
                    utils.axiosPatch(url, data, this);
                }
            },
            deleteClassification() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                utils.axiosDelete(url, this);
            },
            loadList() {
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFilter;
                utils.axiosGet(url, this, 'classifications');
                utils.cleanFeedbackMessage(this);
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            cleanAddFormData() {
                this.description = '';
                this.visualStyle = '';
            },
            showModal(modal) {
                utils.showModal(modal);
            },
        },
        mounted() {
            EventBus.$on("loadList", this.loadList)
            EventBus.$on("setUrlFilter", this.setUrlFilter);
            EventBus.$on("paginate", this.paginate);
            this.loadList();
        }
    }
</script>
