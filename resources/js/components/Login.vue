<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white">Login</div>
                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="login($event)">
                            <input type="hidden" name="_token" :value="csrf_token">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus v-model="email">
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
                                        <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" v-model="password">
                                        <label class="form-label">Senha*</label>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Lembrar-me
                                        </label>
                                    </div>
                                </div>
                            </div>-->
                            <div class="form-group row mb-0 mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-dark text-white w-100">
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-2">
                                <!--<alert-component type="danger" :details="details" title="Erro ao tentar acessar o sistema" v-if="statusLogin == 'erro'"></alert-component>-->
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
        props: ['csrf_token'],
        data() {
            return {
                email: '',
                password: '',
                details: {message: 'erro'},
                statusLogin: ''
            }
        },
        methods: {
            login(e) {
                let url = utils.API_URL + '/api/login'
                let settings = {
                    method:'post',
                    body: new URLSearchParams({
                        'email': this.email,
                        'password': this.password
                    })
                }
                fetch(url, settings)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            if (data.status == 428) {
                                window.location.href = "/login/update-password";
                            } else {
                                this.statusLogin = 'erro';
                                this.details.message = data.error;
                            }
                        } else {
                            if (data.token) {
                                document.cookie = 'token=' + data.token + ':SameSite=Lax';
                            }
                            e.target.submit();
                        }
                    })
                
            }
        }
    }
</script>
