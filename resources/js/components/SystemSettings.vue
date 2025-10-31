<template>
    <div>
        <h3 class="text-white">Configurações do Sistema</h3>
        <div class="card p-3 mt-3">
            <div v-for="(setting, index) in settings" :key="index" class="mb-4 p-3 border border-secondary rounded">
                <h5 class="text-white">{{ setting.title }}</h5>
                <p class="text-muted medium mb-2">{{ setting.description }}</p>

                <!-- Field text -->
                <input v-if="setting.type === 'text'" type="text" class="form-control" :id="setting.attribute" :name="setting.attribute" v-model="setting.value" @blur="update(setting.attribute, setting.value)">

                <!-- Fiels YesNo -->
                <div v-else-if="setting.type === 'YesNo'" class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" :id="setting.attribute" :name="setting.attribute" :checked="setting.value === 'Sim'" @change="toggleYesNo(setting)" />
                    <label class="form-check-label text-white" :for="setting.attribute">
                        {{ setting.value === 'Sim' ? 'Ativado' : 'Desativado' }}
                    </label>
                </div>
            </div>
        </div>
        <alert-component type="danger" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'error'"></alert-component>
        <alert-component type="success" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'success'"></alert-component>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                settings: {data: {}},
                urlBase: utils.API_URL + '/api/v1/system-settings',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false,
            }
        },
        methods: {
            loadList() {
                let url = this.urlBase;
                utils.axiosGet(url, this, 'settings');
                utils.cleanFeedbackMessage(this);                    
            },
            toggleYesNo(setting) {
                setting.value = setting.value === "Sim" ? "Não" : "Sim";
                this.update(setting.attribute, setting.value);
            },
            update(attribute, value) {
                let data = {
                    [attribute]: value
                };
                let url = this.urlBase;
                utils.axiosPatch(url, data, this);
            },
        },
        mounted() {
            this.loadList();
        }
    }
</script>
<style scoped>
    h6 {
        margin-bottom: 0.25rem;
    }
</style>
