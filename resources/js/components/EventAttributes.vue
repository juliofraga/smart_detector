<template>
    <div class="container">
        <search-component 
            :title="translations.event_attributes" 
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
            :placeholder="translations.search_by"
            classSearch="event_attribute"
        ></search-component>
        <div v-if="Object.keys(event_attribute.data).length > 0">
            <table-component
                :title="{
                    id: {title: translations.id, hidden: 'true', type:'text'},
                    display_value: {title: translations.display_value, hidden: 'false', type: 'text'},
                    field_name: {title: translations.field_name, hidden: 'false', type: 'text'},
                    type_field:{title: translations.type, hidden: 'false', type: 'text'},
                    show:{title: translations.show, hidden: 'false', type: 'yesno'},
                    enabled:{title: translations.enabled, hidden: 'false', type: 'yesno'},
                    editar: {title: translations.edit, hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                    updated_at: {title: translations.last_update, hidden: 'true', type: 'datetime'},
                    created_at: {title: translations.creation_date, hidden: 'true', type: 'datetime'},
                }" 
                :data="event_attribute.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                :sectionTitle="translations.registered_event_attributes"
                classList="event_attribute"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component :message="translations.no_event_attribute_found"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "event_attribute"></paginate-component>
        <!-- Modal para adicionar atributo de evento-->
        <modal-component id="modalAdd" :title="translations.add_event_attribute">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="display_value" name="display_value" placeholder="`${translations.display_text}`*" v-model="display_value" @blur="createFieldName($event.target.value)">
                                <label class="form-label">{{ translations.display_text }}*</label>
                                <div id="invalidFeedbackDisplayValue" class="invalid-feedback">
                                    {{ translations.inform_display_text }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="field_name" name="field_name" placeholder="`${translations.field_name}`*" v-model="field_name" @blur="createFieldName($event.target.value)">
                                <label class="form-label">{{ translations.field_name }}*</label>
                                <div id="invalidFeedbackFieldName" class="invalid-feedback">
                                    {{ translations.inform_field_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="type_field" name="type_field" placeholder="`${translations.type_field}`*" v-model="type_field" style="background-color: white;">
                                    <option value="">{{ translations.select }}...</option>
                                    <option value="text">{{ translations.general_text }}</option>
                                    <option value="textarea">{{ translations.textarea }}</option>
                                </select>
                                <label class="form-label">{{ translations.type_field }}*</label>
                                <div id="invalidFeedbackTypeField" class="invalid-feedback">
                                    {{ translations.inform_type_field }}
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
        <modal-component id="modalUpdate" :title="translations.update_event_attribute">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="display_valueUpdate" name="display_valueUpdate" placeholder="`${translations.display_text}`*" v-model="$store.state.item.display_value">
                                <label class="form-label">{{ translations.display_text }}*</label>
                                <div id="invalidFeedbackDisplayValueUpdate" class="invalid-feedback">
                                    {{ translations.inform_display_text }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-secondary text-light" id="field_nameUpdate" name="field_nameUpdate" placeholder="`${translations.field_name}`*" v-model="$store.state.item.field_name" readonly>
                                <label class="form-label text-light">{{ translations.field_name }}*</label>
                                <div id="invalidFeedbackFieldNameUpdate" class="invalid-feedback">
                                    {{ translations.inform_field_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="type_fieldUpdate" name="type_fieldUpdate" placeholder="`${translations.type_field}`*" v-model="$store.state.item.type_field" style="background-color: white;">
                                    <option value="text">{{ translations.general_text }}</option>
                                    <option value="textarea">{{ translations.textarea }}</option>
                                </select>
                                <label class="form-label">{{ translations.type_field }}*</label>
                                <div id="invalidFeedbackTypeFieldUpdate" class="invalid-feedback">
                                    {{ translations.inform_type_field }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="show" name="show" placeholder="`${translations.show_field}`*" v-model="$store.state.item.show" style="background-color: white;">
                                    <option value="0">{{ translations.no }}</option>
                                    <option value="1">{{ translations.yes }}</option>
                                </select>
                                <label class="form-label">{{ translations.show_field }}*</label>
                                <div id="invalidFeedbackShow" class="invalid-feedback">
                                    {{ translations.inform_show_field }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="enabled" name="enabled" placeholder="`${translations.enable_field}`*" v-model="$store.state.item.enabled" style="background-color: white;">
                                    <option value="0">{{ translations.no }}</option>
                                    <option value="1">{{ translations.yes }}</option>
                                </select>
                                <label class="form-label">{{ translations.enable_field }}*</label>
                                <div id="invalidFeedbackEnabled" class="invalid-feedback">
                                    {{ translations.inform_enable_field }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label class="form-label text-light"><i>{{ translations.creation_date }}: {{ $store.state.item.created_at | formatDateTimeStamp}}</i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label text-light"><i>{{ translations.last_update }}: {{ $store.state.item.updated_at | formatDateTimeStamp}}</i></label>
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
        props: ['translations'],
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
            },
            paginate(l) {
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadList();
                }
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
