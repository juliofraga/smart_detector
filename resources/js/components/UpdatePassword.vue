<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white">{{ translations.password_update }}</div>
                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="login($event)">
                            <input type="hidden" name="_token" :value="csrf_token">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus v-model="email" placeholder="`${translations.email}`*" readonly>
                                        <label class="form-label">{{ translations.email }}*</label>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="passwordTemp" :type="showPasswordTemp ? 'text' : 'password'" class="form-control" name="passwordTemp" value="" required autocomplete="current-password" v-model="passwordTemp" placeholder="`${translations.temporary_password}`*">
                                        <label class="form-label">{{ translations.temporary_password }}*</label>
                                        <span class="password-toggle" @click="showPasswordTemp = !showPasswordTemp">
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
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="passwordNew" :type="showPasswordNew ? 'text' : 'password'" class="form-control" name="passwordNew" value="" required autocomplete="current-password" v-model="passwordNew" placeholder="`${translations.new_password}`*">
                                        <span class="password-toggle" @click="showPasswordNew = !showPasswordNew">
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
                                        <label class="form-label">{{ translations.new_password }}*</label>
                                    </div>
                                    <div v-if="passComplexityActivated && passwordStarted" class="mt-2 text-white small">
                                        <ul class="mb-0">
                                            <li :class="{'text-success': passwordLengthOk, 'text-danger': !passwordLengthOk}">
                                                {{ translations.mininum_characters }}
                                            </li>
                                            <li :class="{'text-success': passwordUpperOk, 'text-danger': !passwordUpperOk}">
                                                {{ translations.one_capital_letter }}
                                            </li>
                                            <li :class="{'text-success': passwordLowerOk, 'text-danger': !passwordLowerOk}">
                                                {{ translations.one_lowercase_letter }}
                                            </li>
                                            <li :class="{'text-success': passwordNumberOk, 'text-danger': !passwordNumberOk}">
                                                {{ translations.one_number }}
                                            </li>
                                            <li :class="{'text-success': passwordSpecialOk, 'text-danger': !passwordSpecialOk}">
                                                {{ translations.one_spcecial_character }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="passwordNewRepeat" :type="showPasswordNewRepeat ? 'text' : 'password'" class="form-control" name="passwordNewRepeat" required autocomplete="current-password" v-model="passwordNewRepeat" value="" placeholder="`${translations.repeat_new_password}`*">
                                        <span class="password-toggle" @click="showPasswordNewRepeat = !showPasswordNewRepeat">
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
                                        <label class="form-label">{{ translations.repeat_new_password }}*</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-dark text-white w-100" :disabled="isButtonDisabled">
                                            {{ translations.update }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-2">
                                <alert-component :type="typeAlert" :details="details" :title="title" v-if="statusLogin != ''"></alert-component>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        props: ['csrf_token', 'email'],
        data() {
            return {
                passwordTemp: '',
                passwordNew: '',
                passwordNewRepeat: '',
                details: {message: 'erro'},
                statusLogin: '',
                title: 'Não foi possível atualizar a senha',
                typeAlert: '',
                passComplexityActivated: true,
                passwordStarted: false,
                passwordLengthOk: false,
                passwordUpperOk: false,
                passwordLowerOk: false,
                passwordNumberOk: false,
                passwordSpecialOk: false,
                settings: {data: {}},
                urlBaseSettings: utils.API_URL + '/api/v1/system-settings',
                showPasswordTemp: false,
                showPasswordNew: false,
                showPasswordNewRepeat: false,
                translations: {}
            }
        },
        methods: {
            login(e) {
                if (this.passwordNew != this.passwordNewRepeat) {
                    this.statusLogin = 'error';
                    this.details.message = "Senhas não conferem, verifique novamente!";
                    this.typeAlert = 'danger'
                    return;
                } else if (this.passwordNew == '' || this.passwordNewRepeat == '') {
                    this.statusLogin = 'error';
                    this.details.message = "A nova senha não pode ser em branco, verifique novamente!";
                    this.typeAlert = 'danger'
                    return;
                }
                let url = utils.API_URL + '/api/user/update-password'
                let configuracoes = {
                    method:'post',
                    body: new URLSearchParams({
                        'email': this.email,
                        'password': this.passwordTemp,
                        'passwordNew': this.passwordNew
                    })
                }
                fetch(url, configuracoes)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            this.statusLogin = 'error';
                            this.details.message = data.error;
                            this.typeAlert = 'danger'
                        } else {
                            this.statusLogin = 'success';
                            this.title = 'Senha alterada com sucesso, você está sendo redirecionado para a página de login!';
                            this.typeAlert = 'success';
                            setTimeout(() => {
                                window.location.href = "/login";
                            }, 3000);
                                
                        }
                    })   
            },
            loadSettings() {
                let url = this.urlBaseSettings;
                utils.axiosGet(url, this, 'settings');
            }
        },
        watch: {
            passwordNew(newVal) {
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
            }
        },
        mounted() {
            if (this.email == '') {
                window.location.href = '/login/';
            }
            this.loadSettings();
            utils.loadTranslations(this, 'user_domain__buttons', 'translations');
        },
        computed: {
            isButtonDisabled() {
                if (!this.passComplexityActivated) {
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
