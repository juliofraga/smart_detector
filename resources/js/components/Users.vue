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
                sectionTitle="Usuários Cadastrados"
            ></table-component>
        </div>
        <div v-else-if="loaded === true">
            <no-itens-component message="Nenhum usuário encontrado"></no-itens-component>
        </div>
        <div v-else-if="loaded === false">
            <spinner-component></spinner-component>
        </div>
        <div class="row mt-4">
            <div class="col col-10">
                <paginate-component>
                    <li v-for="l, key in users.links" :key="key" class="page-item" @click="paginate(l)">
                        <div v-if="l.active">
                            <a class="page-link dark-paginantion-active" v-html="l.label" 
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
                            <a class="page-link dark-pagination" 
                            v-if="
                                l.url != null && 
                                (key == users.current_page || 
                                key == users.current_page - 1 || 
                                key == users.current_page + 1 || 
                                key == 0 ||
                                (users.current_page == 1 && key == 3) ||
                                key == users.last_page + 1 || 
                                (users.current_page == users.last_page && key == users.last_page - 2))"
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
        <!-- Modal para atualizar usuários -->
        <modal-component id="modalAtualizarUsuario" title="Atualizar Usuário">
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
                                <select class="form-control" id="profileUpdate" name="profileUpdate" placeholder="Perfil*" style="background-color: white;" v-model="$store.state.item.profile">
                                    <option value="admin">Administrador</option>
                                    <option value="user">Usuário</option>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label"><i>Último acesso: {{ $store.state.item.last_access | formatDateTime}}</i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label"><i>Data de criação: {{ $store.state.item.created_at | formatDateTimeStamp}}</i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label"><i>Última atualização: {{ $store.state.item.updated_at | formatDateTimeStamp}}</i></label>
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
        <!-- Modal para confirmar remoção de usuário -->
        <modal-component id="modalConfirmarDeletar" title="Você tem certeza?">
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col col-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" @click="showModal('modalAtualizarUsuario')">Não</button>
                    </div>
                    <div class="col col-6">
                        <button type="button" class="btn btn-danger text-white w-100" @click="deleteUser()">Sim</button>
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
                        let url = this.urlBase;
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
            update() {
                if (this.$store.state.item.name == ''){
                    document.getElementById('nameUpdate').classList.add('is-invalid');
                } else if (this.passwordUpdate != this.repeatPasswordUpdate) {
                    document.getElementById('repeatPasswordUpdate').classList.add('is-invalid');
                } else if (this.$store.state.item.profile == '') {
                    document.getElementById('profileUpdate').classList.add('is-invalid');
                } else {
                    if (document.getElementById('nameUpdate').classList.contains('is-invalid')) {
                        document.getElementById('nameUpdate').classList.remove('is-invalid');
                    }
                    if (document.getElementById('repeatPasswordUpdate').classList.contains('is-invalid')) {
                        document.getElementById('repeatPasswordUpdate').classList.remove('is-invalid');
                    }
                    let data = {
                        name: this.$store.state.item.name,
                        email: this.$store.state.item.email,
                        profile: this.$store.state.item.profile,
                        password: this.passwordUpdate
                    };
                    let url = this.urlBase + '/' + this.$store.state.item.id;
                    axios.patch(url, data)
                        .then(response => {
                            this.status = 'success';
                            this.feedbackTitle = "Usuário atualizado com sucesso";
                            utils.closeModal('modalAtualizarUsuario');
                            this.loadUserList();
                        })
                        .catch(errors => {
                            this.status = 'error';
                            this.feedbackTitle = "Erro ao atualizar usuário";
                            utils.closeModal('modalAtualizarUsuario');
                            this.feedbackMessage = {
                                message: errors.response.data.message,
                                data: errors.response.data.errors
                            };
                        })
                }
            },
            deleteUser() {
                let url = this.urlBase + '/' + this.$store.state.item.id;
                axios.delete(url)
                    .then(response => {
                        this.status = 'success';
                        this.feedbackTitle = "Usuário deletado com sucesso";
                        utils.closeModal('modalConfirmarDeletar');
                        this.loadUserList();
                    })
                    .catch(errors => {
                        this.status = 'error';
                        this.feedbackTitle = "Erro ao deletar usuário";
                        utils.closeModal('modalConfirmarDeletar');
                        this.feedbackMessage = {
                            message: errors.response.data.message,
                            data: errors.response.data.errors
                        };
                    })
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
            showModal(modal) {
                utils.showModal(modal);
            },
        },
        mounted() {
            EventBus.$on("loadUserList", this.loadUserList)
            EventBus.$on("setUrlFilter", this.setUrlFilter);
            this.loadUserList();
        }
    }
</script>
