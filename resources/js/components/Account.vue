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
                                <input type="password" class="form-control" id="password" name="password" placeholder="Senha" v-model="password">
                                <label class="form-label">Senha</label>
                                <div id="invalidFeedbackPassword" class="invalid-feedback">
                                    Informe a senha.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="repeatPassword" name="repeatPassword" placeholder="Repetir Senha" v-model="repeatPassword">
                                <label class="form-label">Repetir Senha</label>
                                <div id="invalidFeedbackPassword" class="invalid-feedback">
                                    Esta senha não confere com a senha digitada no campo anterior ou está vazio.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success text-white w-100" @click="update()">Atualizar</button>
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
                            <alert-component type="danger" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'error'"></alert-component>
                            <alert-component type="success" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'success'"></alert-component>
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
                feedbackMessage: {},
                feedbackTitle: '',
                status: ''
            }
        },
        methods: {
            update() {
                if (this.name == ''){
                    document.getElementById('name').classList.add('is-invalid');
                } else {
                    if (document.getElementById('name').classList.contains('is-invalid')) {
                        document.getElementById('name').classList.remove('is-invalid');
                    }
                    if (this.password != this.repeatPassword) {
                        document.getElementById('repeatPassword').classList.add('is-invalid');
                    } else {
                        if (document.getElementById('repeatPassword').classList.contains('is-invalid')) {
                            document.getElementById('repeatPassword').classList.remove('is-invalid');
                        }
                        let data = {
                            name: this.name,
                            password: this.password
                        };
                        let url = this.urlBase + '/' + this.id;
                        utils.axiosPatch(url, data, this);
                        utils.cleanFeedbackMessage(this); 
                    }
                }
            }
        }
    }
</script>
