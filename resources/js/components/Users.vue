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
                },
                searchDate: {
                    show: false
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
                                <input type="password" :type="showPassword ? 'text' : 'password'" class="form-control" id="password" name="password" placeholder="Senha*" v-model="password" @input="(checkPasswordStrength('add'))">
                                <span class="password-toggle" @click="showPassword = !showPassword">
                                    <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                        <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                    </svg>
                                </span>
                                <label class="form-label">Senha*</label>
                                <div id="invalidFeedbackPassword" class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                            <!-- Indicador de requisitos da senha -->
                            <ul v-if="passComplexityActivated && passwordStarted" class="mt-2 ps-3" style="font-size: 0.9rem; list-style: none;">
                                <li :style="{ color: passwordChecks.length ? 'green' : 'red' }">• Mínimo de 8 caracteres</li>
                                <li :style="{ color: passwordChecks.uppercase ? 'green' : 'red' }">• Pelo menos uma letra maiúscula</li>
                                <li :style="{ color: passwordChecks.lowercase ? 'green' : 'red' }">• Pelo menos uma letra minúscula</li>
                                <li :style="{ color: passwordChecks.number ? 'green' : 'red' }">• Pelo menos um número</li>
                                <li :style="{ color: passwordChecks.special ? 'green' : 'red' }">• Pelo menos um caractere especial</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-2">
                            <div class="form-floating">
                                <input :type="showPasswordRepeat ? 'text' : 'password'" class="form-control" id="repeatPassword" name="repeatPassword" placeholder="Repetir Senha*" v-model="repeatPassword">
                                <span class="password-toggle" @click="showPasswordRepeat = !showPasswordRepeat">
                                    <svg v-if="!showPasswordRepeat" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                        <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                    </svg>
                                </span>
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
                <add-cancel-buttons-component :disabled="!isPasswordValid"></add-cancel-buttons-component>
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
                                <input type="password" class="form-control" id="passwordUpdate" name="passwordUpdate" placeholder="Senha*" v-model="passwordUpdate" @input="checkPasswordStrength('update')">
                                <label class="form-label">Senha*</label>
                                <div id="invalidFeedbackPasswordUpdate" class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                            <!-- Indicador de requisitos da senha -->
                            <ul v-if="passComplexityActivated && passwordUpdateStarted" class="mt-2 ps-3" style="font-size: 0.9rem; list-style: none;">
                                <li :style="{ color: passwordChecks.length ? 'green' : 'red' }">• Mínimo de 8 caracteres</li>
                                <li :style="{ color: passwordChecks.uppercase ? 'green' : 'red' }">• Pelo menos uma letra maiúscula</li>
                                <li :style="{ color: passwordChecks.lowercase ? 'green' : 'red' }">• Pelo menos uma letra minúscula</li>
                                <li :style="{ color: passwordChecks.number ? 'green' : 'red' }">• Pelo menos um número</li>
                                <li :style="{ color: passwordChecks.special ? 'green' : 'red' }">• Pelo menos um caractere especial</li>
                            </ul>
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
                <updates-button-component :disabled="!(isPasswordValid || !passwordUpdateStarted)"></updates-button-component>                          
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
                urlBaseSettings: utils.API_URL + '/api/v1/system-settings',
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
                profiles: {data: {}},
                settings: {data: {}},
                passComplexityActivated: true,
                passwordStarted: false,
                passwordUpdateStarted: false,
                passwordChecks: {
                    length: false,
                    uppercase: false,
                    lowercase: false,
                    number: false,
                    special: false,
                },
                showPassword: false,
                showPasswordRepeat: false
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
                utils.cleanAddFormData(this, ['name', 'email', 'password', 'profile', 'repeatPassword', 'passwordUpdate', 'repeatPasswordUpdate']);
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
            checkPasswordStrength(action) {
                if (!this.passComplexityActivated) return;
                let pass = '';
                if (action == 'add') {
                    pass = this.password;
                    this.passwordStarted = pass.length > 0;
                } else {
                    pass = this.passwordUpdate;
                    this.passwordUpdateStarted = pass.length > 0;
                }             
                

                this.passwordChecks.length = pass.length >= 8;
                this.passwordChecks.uppercase = /[A-Z]/.test(pass);
                this.passwordChecks.lowercase = /[a-z]/.test(pass);
                this.passwordChecks.number = /[0-9]/.test(pass);
                this.passwordChecks.special = /[\W_]/.test(pass);
            },
            loadSettings() {
                let url = this.urlBaseSettings;
                utils.axiosGet(url, this, 'settings');
            }
        },
        computed: {
            isPasswordValid() {
                if (!this.passComplexityActivated) {
                    return true;
                }
                const checks = this.passwordChecks;
                return (
                    checks.length &&
                    checks.uppercase &&
                    checks.lowercase &&
                    checks.number &&
                    checks.special
                );
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
            this.loadProfiles();
            this.loadSettings();
        },
        watch: {
            settings(newVal) {
                if (Array.isArray(newVal) && newVal.length > 0) {
                const passComplexity = newVal.find(item => item.attribute === 'pass_complexity');
                    if (passComplexity) {
                        if (passComplexity.value == 'Yes') {
                            this.passComplexityActivated = true;
                        } else {
                            this.passComplexityActivated = false;
                        }
                    } else {
                        this.passComplexityActivated = true;
                    }
                }
            }
        },
    }
</script>
<style scoped>
    ul li {
        transition: color 0.2s ease;
    }
    ul li::before {
        content: "✖ ";
        color: red;
    }
    ul li[style*="green"]::before {
        content: "✔ ";
        color: green;
    }
</style>
