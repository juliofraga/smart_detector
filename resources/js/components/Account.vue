<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <h4 class="text-white"><center>Minha Conta</center></h4>
                <div class="form-group">
                    <input type="hidden" class="form-control" id="id" name="id" v-model="id">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nome*" v-model="name">
                                <label class="form-label">Nome*</label>
                                <div id="invalidFeedbackName" class="invalid-feedback">
                                    Informe o nome do usuário.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail*" v-model="email" readonly>
                                <label class="form-label">E-mail*</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-floating">
                                <input type="password" :type="showPassword ? 'text' : 'password'" class="form-control" id="password" name="password" placeholder="Senha" v-model="password">
                                <label class="form-label">Senha</label>
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
                                <div id="invalidFeedbackPassword" class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                            <div v-if="passComplexityActivated && passwordStarted" class="mt-2 text-white small">
                                <ul class="mb-0">
                                    <li :class="{'text-success': passwordLengthOk, 'text-danger': !passwordLengthOk}">
                                        Mínimo de 8 caracteres
                                    </li>
                                    <li :class="{'text-success': passwordUpperOk, 'text-danger': !passwordUpperOk}">
                                        Pelo menos uma letra maiúscula
                                    </li>
                                    <li :class="{'text-success': passwordLowerOk, 'text-danger': !passwordLowerOk}">
                                        Pelo menos uma letra minúscula
                                    </li>
                                    <li :class="{'text-success': passwordNumberOk, 'text-danger': !passwordNumberOk}">
                                        Pelo menos um número
                                    </li>
                                    <li :class="{'text-success': passwordSpecialOk, 'text-danger': !passwordSpecialOk}">
                                        Pelo menos um caractere especial
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-floating">
                                <input :type="showPasswordRepeat ? 'text' : 'password'" class="form-control" id="repeatPassword" name="repeatPassword" placeholder="Repetir Senha" v-model="repeatPassword">
                                <label class="form-label">Repetir Senha</label>
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
                                <div id="invalidFeedbackPassword" class="invalid-feedback">
                                    Esta senha não confere com a senha digitada no campo anterior ou está vazio.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success text-white w-100" @click="update()" :disabled="isButtonDisabled">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                    <path d="M11 2H9v3h2z"/>
                                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                                </svg>
                                Atualizar
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label class="form-label text-white"><i>Último acesso:</i> {{ user.last_access | formatDateTime }}</label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label class="form-label text-white"><i>Data de criação:</i> {{ user.created_at | formatDateTimeStamp }}</label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <label class="form-label text-white"><i>Última atualização: </i> {{ user.updated_at | formatDateTimeStamp }}</label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <toast-component ref="toastError" type="danger" :details="feedbackMessage" :title="feedbackTitle" v-show="status == 'error'"></toast-component>
                            <toast-component ref="toastSuccess" type="success" :details="feedbackMessage" :title="feedbackTitle" v-show="status == 'success'"></toast-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        props: ['user'],
        data() {
            return {
                name: this.user.name,
                email: this.user.email,
                id: this.user.id,
                password: '',
                repeatPassword: '',
                urlBase: utils.API_URL + '/api/v1/user',
                urlBaseSettings: utils.API_URL + '/api/v1/system-settings',
                feedbackMessage: {},
                feedbackTitle: '',
                status: '',
                passComplexityActivated: true,
                passwordStarted: false,
                passwordLengthOk: false,
                passwordUpperOk: false,
                passwordLowerOk: false,
                passwordNumberOk: false,
                passwordSpecialOk: false,
                settings: {data: {}},
                showPassword: false,
                showPasswordRepeat: false,
            }
        },
        watch: {
            password(newVal) {
                if (this.passComplexityActivated) {
                    this.passwordStarted = newVal.length > 0;
                    this.passwordLengthOk = newVal.length >= 8;
                    this.passwordUpperOk = /[A-Z]/.test(newVal);
                    this.passwordLowerOk = /[a-z]/.test(newVal);
                    this.passwordNumberOk = /\d/.test(newVal);
                    this.passwordSpecialOk = /[^A-Za-z0-9]/.test(newVal);
                }
            },
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
            },
            status(newVal) {
                if (newVal === 'success' || newVal === 'error') {
                    this.$nextTick(() => {
                        if (newVal === 'success') {
                            this.$refs.toastSuccess?.show()
                        } else if (newVal === 'error') {
                            this.$refs.toastError?.show()
                        }
                        
                    })
                }
            }
        },
        methods: {
            update() {
                utils.removeRequiredFieldMessage(['name', 'repeatPassword']);
                if (this.name == ''){
                    utils.showRequiredFieldMessage('name');
                } else {
                    if (this.password != this.repeatPassword) {
                        utils.showRequiredFieldMessage('repeatPassword');
                    } else {
                        utils.removeRequiredFieldMessage(['name', 'repeatPassword']);
                        let data = {
                            name: this.name,
                            password: this.password
                        };
                        let url = this.urlBase + '/' + this.id;
                        utils.axiosPatch(url, data, this);
                        utils.cleanFeedbackMessage(this); 
                    }
                }
            },
            loadSettings() {
                let url = this.urlBaseSettings;
                utils.axiosGet(url, this, 'settings');
            }
        },
        mounted() {
            this.loadSettings();
        },
        computed: {
            isButtonDisabled() {
                if (!this.passComplexityActivated) {
                    return false
                }

                if (this.passComplexityActivated && !this.passwordStarted) {
                    return false
                }

                if (this.passComplexityActivated && this.passwordStarted) {
                    const allOk = this.passwordLengthOk &&
                                this.passwordUpperOk &&
                                this.passwordLowerOk &&
                                this.passwordNumberOk &&
                                this.passwordSpecialOk
                    return !allOk
                }

                return true
            }
        }
    }
</script>
