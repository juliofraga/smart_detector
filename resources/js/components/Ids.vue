<template>
    <div class="container">
        <search-component 
            :title="translations.intrusion_detection_system" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdd'
                },
                search: {
                    show: true,
                    fields: ['name', 'hostname', 'ip_local']
                },
                clear: {
                    show: true,
                },
                searchDate: {
                    show: false
                }
            }" 
            :placeholder="translations.search_by"
            classSearch="ids"
        ></search-component>
        <div v-if="Object.keys(ids.data).length > 0">
            <table-component
                :title="{
                    id: {title: translations.id, hidden: 'false', type:'text'},
                    name: {title: translations.name, hidden: 'false', type:'text'},           
                    hostname: {title: translations.hostname, hidden: 'false', type: 'text'},
                    ip_local: {title: translations.ip_address, hidden: 'false', type: 'text'},
                    created_at: {title: translations.creation_date, hidden: 'false', type: 'timestamp'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                }" 
                :data="ids.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                :sectionTitle="translations.registered_ids"
                classList="type"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component :message="translations.no_ids_found"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "ids"></paginate-component>
        <!-- Modal para adicionar IDS -->
        <modal-component id="modalAdd" :title="translations.add_ids">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="`${translations.name}`*" v-model="name">
                                <label class="form-label">{{ translations.name }}*</label>
                                <div id="invalidFeedbackName" class="invalid-feedback">
                                    {{ translations.inform_name }}.
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="hostname" name="hostname" placeholder="`${translations.hostname}`" v-model="hostname">
                                <label class="form-label">{{ translations.hostname }}</label>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="ip_local" name="ip_local" placeholder="`${translations.ip_address}`" v-model="ip_local">
                                <label class="form-label">{{ translations.ip_address }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <add-cancel-buttons-component></add-cancel-buttons-component>
            </template>
        </modal-component>
        <!-- Modal para atualizar IDS -->
        <modal-component id="modalUpdate" :title="translations.update_ids">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nameUpdate" name="nameUpdate" placeholder="`${translations.name}`*" v-model="$store.state.item.name">
                                <label class="form-label">{{ translations.name }}*</label>
                                <div id="invalidFeedbackNameUpdate" class="invalid-feedback">
                                    {{ translations.inform_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="hostnameUpdate" name="hostnameUpdate" placeholder="`${translations.hostname}`" v-model="$store.state.item.hostname">
                                <label class="form-label">{{ translations.hostname }}</label>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="ip_localUpdate" name="ip_localUpdate" placeholder="`${translations.ip_address}`" v-model="$store.state.item.ip_local">
                                <label class="form-label">{{ translations.ip_address }}</label>
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
        <!-- Modal para confirmar remoção de IDS -->
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
                ids: {data: {}},
                urlBase: utils.API_URL + '/api/v1/ids',
                urlPaginate: '',
                urlFilter: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false,
                name: '',
                hostname: '',
                ip_local: ''
            }
        },
        methods: {
            save() {
                if (utils.fieldsValidate(['name'], this)) {
                    let data = {
                        name: this.name,
                        hostname: this.hostname,
                        ip_local: this.ip_local
                    };
                    let url = this.urlBase;
                    utils.axiosPost(url, data, this);                        
                }
            },
            update() {
                utils.removeRequiredFieldMessage(['nameUpdate']);
                if (this.$store.state.item.name == ''){
                    utils.showRequiredFieldMessage('nameUpdate');
                } else {
                    utils.removeRequiredFieldMessage(['nameUpdate']);
                    let data = {
                        name: this.$store.state.item.name,
                        hostname: this.$store.state.item.hostname,
                        ip_local: this.$store.state.item.ip_local
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
                utils.axiosGet(url, this, 'ids');
                utils.cleanFeedbackMessage(this);                    
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            cleanAddFormData() {
                utils.cleanAddFormData(this, ['name', 'hostname', 'ip_local']);
            },
            showModal(modal) {
                utils.showModal(modal);
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
