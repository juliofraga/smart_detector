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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success text-white" @click="save()">Salvar</button>
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
                <button type="button" class="btn btn-success text-white" @click="update()">Atualizar</button>
                <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-dismiss="modal" data-bs-target="#modalConfirmDelete">Deletar</button> 
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>                            
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
            this.loadList();
            this.loadProfiles();
        }
    }
</script>
