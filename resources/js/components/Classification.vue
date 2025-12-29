<template>
    <div class="container">
        <search-component 
            :title="translations.risk_classification" 
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
                },
                searchDate: {
                    show: false
                }
            }" 
            :placeholder="translations.search_by"
            classSearch="classification"
        ></search-component>
        <div v-if="Object.keys(classifications.data).length > 0">
            <table-component
                :title="{
                    id: {title: translations.id, hidden: 'true', type:'text'},
                    description: {title: translations.description, hidden: 'false', type:'text'},
                    visual_style: {title: translations.style, hidden: 'false', type:'badge'},               
                    created_at: {title: translations.creation_date, hidden: 'false', type: 'timestamp'},
                    editar: {title: translations.edit, hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                }" 
                :data="classifications.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                :sectionTitle="translations.registered_risk_classifications"
                classList="classification"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component :message="translations.no_risk_found"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "classifications"></paginate-component>
        <!-- Modal para adicionar classificações de risco -->
        <modal-component id="modalAdd" :title="translations.add_risk_classification">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="description" name="description" placeholder="`${translations.description}`*" v-model="description">
                                <label class="form-label">{{ translations.description }}*</label>
                                <div id="invalidFeedbackDescricao" class="invalid-feedback">
                                    {{ translations.inform_description }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="visualStyle" name="visualStyle" placeholder="`${translations.style}`*" v-model="visualStyle" style="background-color: white;">
                                    <option value="">{{ translations.select }}...</option>
                                    <option value="primary" class="bg-primary text-white">Primary</option>
                                    <option value="secondary" class="bg-secondary text-white">Secondary</option>
                                    <option value="success" class="bg-success text-white">Success</option>
                                    <option value="danger" class="bg-danger text-white">Danger</option>
                                    <option value="warning" class="bg-warning">Warning</option>
                                    <option value="info" class="bg-info">Info</option>
                                    <option value="light" class="bg-light">Light</option>
                                    <option value="dark" class="bg-dark text-white" style="color:">Dark</option>
                                </select>
                                <label class="form-label">{{ translations.style }}*</label>
                                <div id="invalidFeedbackEstilo" class="invalid-feedback">
                                    {{ translations.specify_alert_style }}
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
        <!-- Modal para atualizar classificações de risco -->
        <modal-component id="modalUpdate" :title="translations.update_risk_classification">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="descriptionUpdate" name="descriptionUpdate" placeholder="`${translations.description}`*" v-model="$store.state.item.description">
                                <label class="form-label">{{ translations.description }}*</label>
                                <div id="invalidFeedbackDescricaoUpdate" class="invalid-feedback">
                                    {{ translations.inform_description }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="visualStyleUpdate" name="visualStyleUpdate" placeholder="`${translations.style}`*" v-model="$store.state.item.visual_style" style="background-color: white;">
                                    <option value="">{{ translations.select }}...</option>
                                    <option value="primary" class="bg-primary text-white">Primary</option>
                                    <option value="secondary" class="bg-secondary text-white">Secondary</option>
                                    <option value="success" class="bg-success text-white">Success</option>
                                    <option value="danger" class="bg-danger text-white">Danger</option>
                                    <option value="warning" class="bg-warning">Warning</option>
                                    <option value="info" class="bg-info">Info</option>
                                    <option value="light" class="bg-light">Light</option>
                                    <option value="dark" class="bg-dark text-white" style="color:">Dark</option>
                                </select>
                                <label class="form-label">{{ translations.style }}*</label>
                                <div id="invalidFeedbackEstiloUpdate" class="invalid-feedback">
                                    {{ translations.specify_alert_style }}
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
        <!-- Modal para confirmar remoção de classificação de risco -->
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
                utils.removeRequiredFieldMessage(['descriptionUpdate', 'visualStyleUpdate']);
                if (this.$store.state.item.description == ''){
                    utils.showRequiredFieldMessage('descriptionUpdate');
                } else if (this.$store.state.item.visual_style == '') {
                    utils.showRequiredFieldMessage('visualStyleUpdate');
                } else {
                    utils.removeRequiredFieldMessage(['descriptionUpdate', 'visualStyleUpdate']);
                    let data = {
                        description: this.$store.state.item.description,
                        visual_style: this.$store.state.item.visual_style
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
                utils.axiosGet(url, this, 'classifications');
                utils.cleanFeedbackMessage(this);
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            cleanAddFormData() {
                utils.cleanAddFormData(this, ['description', 'visualStyle']);
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
