<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white">Atualização de Senha</div>
                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="login($event)">
                            <input type="hidden" name="_token" :value="csrf_token">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus v-model="email" placeholder="E-mail*" readonly>
                                        <label class="form-label">E-mail*</label>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="passwordTemp" type="password" class="form-control" name="passwordTemp" value="" required autocomplete="current-password" v-model="passwordTemp" placeholder="Senha Temporária*">
                                        <label class="form-label">Senha Temporária*</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="passwordNew" type="password" class="form-control" name="passwordNew" value="" required autocomplete="current-password" v-model="passwordNew" placeholder="Nova Senha*">
                                        <label class="form-label">Nova Senha*</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="passwordNewRepeat" type="password" class="form-control" name="passwordNewRepeat" required autocomplete="current-password" v-model="passwordNewRepeat" value="" placeholder="Repetir Nova Senha*">
                                        <label class="form-label">Repetir Nova Senha*</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-dark text-white w-100">
                                            Alterar
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
                
            }
        },
        mounted() {
            if (this.email == '') {
                window.location.href = '/login/';
            }
        }
    }
</script>
