<template>
    <div class="container">
        <search-component 
            title="Usuários" 
            :buttons=" {
                add: {
                    show: true,
                    modalId: '#modalAdicionarUsuario'
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
                    profile: {title: 'Perfil', hidden: 'false', type:'text'},
                    last_access: {title: 'Último Acesso', hidden: 'false', type:'datetime'},
                    editar: {title: 'Editar', hidden: 'false', type: 'buttonModal', modalId: '#modalAtualizarUsuario'},
                    updated_at: {title: 'Última Atualização', hidden: 'true', type: 'datetime'},
                    created_at: {title: 'Data de Criação', hidden: 'true', type: 'datetime'},
                    
                }" 
                :data="users.data"
                :status="status"
                :feedbackMessage="feedbackMessage"
                :feedbackTitle="feedbackTitle"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <div class="row mt-4">
            <div class="col col-10">
                <paginate-component>
                    <li v-for="l, key in users.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginate(l)">
                        <div v-if="l.active">
                            <a class="page-link paginate_link_activated" v-html="l.label" 
                            v-if="
                                key == users.current_page || 
                                key == users.current_page - 1 || 
                                key == users.current_page + 1 || 
                                key == 0 ||
                                (users.current_page == 1 && key == 3) ||
                                key == users.last_page + 1 || 
                                (users.current_page == users.last_page && key == users.last_page - 2)"
                        ></a>
                        </div>
                        <div v-else>
                            <a class="page-link paginate_link" 
                            v-if="
                                key == users.current_page || 
                                key == users.current_page - 1 || 
                                key == users.current_page + 1 || 
                                key == 0 ||
                                (users.current_page == 1 && key == 3) ||
                                key == users.last_page + 1 || 
                                (users.current_page == users.last_page && key == users.last_page - 2)"
                        >{{ l.label | formatNextPrevButton }}</a>
                        </div>
                        
                    </li>
                </paginate-component>
            </div>
        </div>
        <!-- Modal para adicionar usuários -->
        <modal-component id="modalAdicionarUsuario" title="Adicionar Usuário">
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
                                    <option value="admin">Administrador</option>
                                    <option value="user">Usuário</option>
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
                loaded: false
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
                            profile: this.profile,
                            password: this.password
                        };
                        let url = this.urlBase + '/store';
                        axios.post(url, data)
                            .then(response => {
                                this.status = 'success';
                                this.feedbackTitle = "Usuário adicionado com sucesso";
                                utils.closeModal('modalAdicionarUsuario');
                                this.loadUserList();
                                this.cleanAddUserFormData();
                            })
                            .catch(errors => {
                                this.status = 'error';
                                this.feedbackTitle = "Erro ao adicionar usuário";
                                utils.closeModal('modalAdicionarUsuario');
                                this.feedbackMessage = {
                                    mensagem: errors.response.data.message,
                                    data: errors.response.data.errors
                                };
                                this.cleanFeedbackMessage();
                            })
                        
                    }
                }
            },
            cleanAddUserFormData() {
                this.name = '';
                this.email = '';
                this.password = '';
                this.profile= '';
                this.repeatPassword = '';
            },
            cleanFeedbackMessage() {
                setTimeout(() => {
                    this.feedbackMessage =  {};
                    this.feedbackTitle = '';
                    this.status = '';
                }, 10000);
            },
            setUrlFilter(url) {
                this.urlFilter = url;
            },
            loadUserList() {
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFilter;
                axios.get(url)
                    .then(response => {
                        this.users = response.data;
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
            paginate(l) {
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadUserList();
                }
            },
        },
        mounted() {
            EventBus.$on("loadUserList", this.loadUserList)
            EventBus.$on("setUrlFilter", this.setUrlFilter);
            this.loadUserList();
        }
    }
</script>
