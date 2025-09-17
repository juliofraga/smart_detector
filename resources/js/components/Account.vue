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
                            <button type="button" class="btn btn-success text-white w-100" @click="update()">
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
            }
        }
    }
</script>
