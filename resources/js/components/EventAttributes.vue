<template>
    <div class="container">
        <search-component 
            title="Atributos de Eventos" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdd'
                },
                search: {
                    show: true,
                    fields: ['field_name', 'display_value']
                },
                clear: {
                    show: true,
                },
                searchDate: {
                    show: false
                }
            }" 
            placeholder="Buscar por campo ou valor"
            classSearch="event_attribute"
        ></search-component>
        <div v-if="Object.keys(event_attribute.data).length > 0">
            <table-component
                :title="{
                    id: {title: 'ID', hidden: 'true', type:'text'},
                    display_value: {title: 'Valor de Exibição', hidden: 'false', type: 'text'},
                    field_name: {title: 'Nome do Campo', hidden: 'false', type: 'text'},
                    type_field:{title: 'Tipo', hidden: 'false', type: 'text'},
                    show:{title: 'Exibir', hidden: 'false', type: 'yesno'},
                    enabled:{title: 'Habilitado', hidden: 'false', type: 'yesno'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                    updated_at: {title: 'Última Atualização', hidden: 'true', type: 'datetime'},
                    created_at: {title: 'Data de Criação', hidden: 'true', type: 'datetime'},
                }" 
                :data="event_attribute.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                sectionTitle="Atributos de Eventos Cadastrados"
                classList="event_attribute"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhum atributo de evento encontrado"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "event_attribute"></paginate-component>
        <!-- Modal para adicionar atributo de evento-->
        <modal-component id="modalAdd" title="Adicionar Atributo de Evento">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="display_value" name="display_value" placeholder="Texto de Exibição*" v-model="display_value" @blur="createFieldName($event.target.value)">
                                <label class="form-label">Texto de Exibição*</label>
                                <div id="invalidFeedbackDisplayValue" class="invalid-feedback">
                                    Informe o Texto de Exibição
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="field_name" name="field_name" placeholder="Nome do Campo*" v-model="field_name" @blur="createFieldName($event.target.value)">
                                <label class="form-label">Nome do Campo*</label>
                                <div id="invalidFeedbackFieldName" class="invalid-feedback">
                                    Informe o Nome do campo
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="type_field" name="type_field" placeholder="Tipo de Campo*" v-model="type_field" style="background-color: white;">
                                    <option value="">Selecione...</option>
                                    <option value="text">Texto Geral</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                                <label class="form-label">Tipo de Campo*</label>
                                <div id="invalidFeedbackTypeField" class="invalid-feedback">
                                    Informe o estilo do campo
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <add-cancel-buttons-component></add-cancel-buttons-component>
            </template>
        </modal-component>
        <!-- Modal para atualizar atributo de evento -->
        <modal-component id="modalUpdate" title="Atualizar Atributo de Evento">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="display_valueUpdate" name="display_valueUpdate" placeholder="Texto de Exibição*" v-model="$store.state.item.display_value">
                                <label class="form-label">Texto de Exibição*</label>
                                <div id="invalidFeedbackDisplayValueUpdate" class="invalid-feedback">
                                    Informe o Texto de Exibição
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-secondary text-light" id="field_nameUpdate" name="field_nameUpdate" placeholder="Nome do Campo*" v-model="$store.state.item.field_name" readonly>
                                <label class="form-label text-light">Nome do Campo*</label>
                                <div id="invalidFeedbackFieldNameUpdate" class="invalid-feedback">
                                    Informe o Nome do campo
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="type_fieldUpdate" name="type_fieldUpdate" placeholder="Tipo de Campo*" v-model="$store.state.item.type_field" style="background-color: white;">
                                    <option value="text">Texto Geral</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                                <label class="form-label">Tipo de Campo*</label>
                                <div id="invalidFeedbackTypeFieldUpdate" class="invalid-feedback">
                                    Informe o estilo do campo
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="show" name="show" placeholder="Exibir Campo?*" v-model="$store.state.item.show" style="background-color: white;">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                                <label class="form-label">Exibir Campo?*</label>
                                <div id="invalidFeedbackShow" class="invalid-feedback">
                                    Informe se o campo deve ser exibido
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="enabled" name="enabled" placeholder="Habilitar Campo?*" v-model="$store.state.item.enabled" style="background-color: white;">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                                <label class="form-label">Habilitar Campo?*</label>
                                <div id="invalidFeedbackEnabled" class="invalid-feedback">
                                    Informe se o campo deve ser habilitado
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
                <updates-button-component></updates-button-component> 
            </template>
        </modal-component>
        <!-- Modal para confirmar remoção de atributo de evento -->
        <modal-delete-component></modal-delete-component>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                event_attribute: {data: {}},
                urlBase: utils.API_URL + '/api/v1/event-attribute',
                urlPaginate: '',
                urlFilter: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                display_value: '',
                field_name: '',
                type_field: '',
                display_valueUpdate: '',
                field_nameUpdate: '',
                type_fieldUpdate: '',
                loaded: false,
            }
        },
        methods: {
            save() {
                if (utils.fieldsValidate(['display_value', 'field_name', 'type_field'], this)) {
                    let data = {
                        display_value: this.display_value,
                        field_name: this.field_name,
                        type_field: this.type_field
                    };
                    let url = this.urlBase;
                    utils.axiosPost(url, data, this);                        
                }
            },
            update() {
                utils.removeRequiredFieldMessage(['display_valueUpdate', 'field_nameUpdate', 'type_fieldUpdate']);
                if (this.$store.state.item.display_value == ''){
                    utils.showRequiredFieldMessage('display_valueUpdate');
                } else if (this.$store.state.item.type_field == '') {
                    utils.showRequiredFieldMessage('type_fieldUpdate');
                } else {
                    utils.removeRequiredFieldMessage(['display_valueUpdate', 'type_fieldUpdate']);
                    let data = {
                        display_value: this.$store.state.item.display_value,
                        field_name: this.$store.state.item.field_name,
                        type_field: this.$store.state.item.type_field,
                        show: this.$store.state.item.show,
                        enabled: this.$store.state.item.enabled
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
                utils.axiosGet(url, this, 'event_attribute');
                utils.cleanFeedbackMessage(this);                    
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            cleanAddFormData() {
                utils.cleanAddFormData(this, ['display_value', 'field_name', 'type_field']);
            },
            showModal(modal) {
                utils.showModal(modal);
            },
            createFieldName(value) {
                let result = value
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-zA-Z0-9\s_]/g, '')
                    .trim()
                    .replace(/\s+/g, '_')
                    .replace(/_+/g, '_')
                    .toLowerCase();

                if (/^[0-9]/.test(result)) {
                    result = 'col_' + result;
                }
                this.field_name = result;
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
