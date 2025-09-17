<template>
    <div class="container">
        <search-component 
            title="Usuários" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdd'
                },
                search: {
                    show: true,
                    fields: ['name', 'email']
                },
                clear: {
                    show: true,
                }
            }" 
            placeholder="Buscar por nome ou e-mail"
            classSearch="user"
        ></search-component>
        <div v-if="Object.keys(users.data).length > 0">
            <table-component
                :title="{
                    id: {title: 'ID', hidden: 'true', type:'text'},
                    name: {title: 'Nome', hidden: 'false', type:'text'},
                    email: {title: 'E-mail', hidden: 'false', type:'text'},               
                    profile: {title: 'Perfil', hidden: 'false', type: 'profile'},
                    last_access: {title: 'Último Acesso', hidden: 'false', type:'datetime'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalUpdate', buttonType: 'edit'},
                    updated_at: {title: 'Última Atualização', hidden: 'true', type: 'datetime'},
                    created_at: {title: 'Data de Criação', hidden: 'true', type: 'datetime'},
                    
                }" 
                :data="users.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
                sectionTitle="Usuários Cadastrados"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhum usuário encontrado"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <paginate-component :data = "users"></paginate-component>
        <!-- Modal para adicionar usuários -->
        <modal-component id="modalAdd" title="Adicionar Usuário">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome*" v-model="name">
                                <label class="form-label">Name*</label>
                                <div id="invalidFeedbackName" class="invalid-feedback">
                                    Informe o nome.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail*" v-model="email">
                                <label class="form-label">E-mail*</label>
                                <div id="invalidFeedbackEmail" class="invalid-feedback">
                                    Insira um e-mail válido.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="profile" name="profile" placeholder="Perfil*" v-model="profile" style="background-color: white;">
                                    <option value="">Selecione...</option>
                                    <option v-for="profile in profiles" :key="profile.id" :value="profile.id">
                                        {{ profile.description }}
                                    </option>
                                </select>
                                <label class="form-label">Perfil*</label>
                                <div id="invalidFeedbackProfile" class="invalid-feedback">
                                    Informe o perfil do usuário
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Senha*" v-model="password">
                                <label class="form-label">Senha*</label>
                                <div id="invalidFeedbackPassword" class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="repeatPassword" name="repeatPassword" placeholder="Repetir Senha*" v-model="repeatPassword">
                                <label class="form-label">Repetir Senha*</label>
                                <div id="invalidFeedbackRepeatPassword" class="invalid-feedback">
                                    Esta senha não confere com a senha digitada no campo anterior ou está vazio.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                    Cancelar
                </button>
                <button type="button" class="btn btn-success text-white" @click="save()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>
                    Salvar
                </button>
            </template>
        </modal-component>
        <!-- Modal para atualizar usuários -->
        <modal-component id="modalUpdate" title="Atualizar Usuário">
            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nameUpdate" name="nameUpdate" placeholder="Nome*" v-model="$store.state.item.name">
                                <label class="form-label">Nome*</label>
                                <div id="invalidFeedbackNameUpdate" class="invalid-feedback">
                                    Informe o nome completo.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="emailUpdate" name="emailUpdate" placeholder="E-mail*" v-model="$store.state.item.email" readonly>
                                <label class="form-label">E-mail*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <select class="form-control" id="profileUpdate" name="profileUpdate" placeholder="Perfil*" style="background-color: white;" v-model="$store.state.item.profile.id">
                                    <option v-for="profile in profiles" :key="profile.id" :value="profile.id">
                                        {{ profile.description }}
                                    </option>
                                </select>
                                <label class="form-label">Perfil*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="passwordUpdate" name="passwordUpdate" placeholder="Senha*" v-model="passwordUpdate">
                                <label class="form-label">Senha*</label>
                                <div id="invalidFeedbackPasswordUpdate" class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="repeatPasswordUpdate" name="repeatPasswordUpdate" placeholder="Repetir Senha*" v-model="repeatPasswordUpdate">
                                <label class="form-label">Repetir Senha*</label>
                                <div id="invalidFeedbackRepeatPasswordUpdate" class="invalid-feedback">
                                    Esta senha não confere com a senha digitada no campo anterior ou está vazio.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label class="form-label text-light"><i>Último acesso: {{ $store.state.item.last_access | formatDateTime}}</i></label>
                        </div>
                    </div>
                    <div class="row">
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
        <!-- Modal para confirmar remoção de usuário -->
        <modal-delete-component></modal-delete-component>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                users: {data: {}},
                urlBase: utils.API_URL + '/api/v1/user',
                urlBaseProfile: utils.API_URL + '/api/v1/profile',
                urlPaginate: '',
                urlFilter: '',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                name: '',
                email: '',
                password: '',
                repeatPassword: '',
                passwordUpdate: '',
                repeatPasswordUpdate: '',
                profile: '',
                profileUpdate: '',
                loaded: false,
                profiles: {data: {}}
            }
        },
        methods: {
            save() {
                if (utils.fieldsValidate(['name', 'email', 'profile', 'password', 'repeatPassword'], this)) {
                    if (this.password != this.repeatPassword) {
                        document.getElementById('repeatPassword').classList.add('is-invalid');
                    } else {
                        if (document.getElementById('repeatPassword').classList.contains('is-invalid')) {
                            document.getElementById('repeatPassword').classList.remove('is-invalid');
                        }
                        let data = {
                            name: this.name,
                            email: this.email,
                            profiles_id: this.profile,
                            password: this.password
                        };
                        let url = this.urlBase;
                        utils.axiosPost(url, data, this);                        
                    }
                }
            },
            update() {
                utils.removeRequiredFieldMessage(['nameUpdate', 'repeatPasswordUpdate']);
                if (this.$store.state.item.name == ''){
                    utils.showRequiredFieldMessage('nameUpdate');
                } else if (this.passwordUpdate != this.repeatPasswordUpdate) {
                    utils.showRequiredFieldMessage('repeatPasswordUpdate');
                } else if (this.$store.state.item.profile == '') {
                    utils.showRequiredFieldMessage('profileUpdate');
                } else {
                    utils.removeRequiredFieldMessage(['nameUpdate', 'repeatPasswordUpdate']);
                    let data = {
                        name: this.$store.state.item.name,
                        email: this.$store.state.item.email,
                        profiles_id: this.$store.state.item.profile.id,
                        password: this.passwordUpdate
                    };
                    let url = this.urlBase + '/' + this.$store.state.item.id;
                    utils.axiosPatch(url, data, this);
                }
            },
            deleteRecord() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                utils.axiosDelete(url, this);
            },
            cleanAddFormData() {
                utils.cleanAddFormData(this, ['name', 'email', 'password', 'profile', 'repeatPassword']);
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            loadList() {
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFilter;
                utils.axiosGet(url, this, 'users');
                utils.cleanFeedbackMessage(this);                    
            },
            loadProfiles() {
                let url = this.urlBaseProfile;
                utils.axiosGet(url, this, 'profiles');
            },
            paginate(l) {
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadList();
                }
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
            this.loadList();
            this.loadProfiles();
        }
    }
</script>
