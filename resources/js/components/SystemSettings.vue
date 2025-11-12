<template>
    <div>
        <h3 class="text-white">Configurações do Sistema</h3>
        <div class="card p-3 mt-3">
            <div v-for="(setting, index) in settings" :key="index" class="mb-4 p-3 border border-secondary rounded">
                <h5 class="text-white">{{ setting.title }}</h5>
                <p class="text-muted medium mb-2">{{ setting.description }}</p>

                <!-- Field text -->
                <input v-if="setting.type === 'text'" type="text" class="form-control" :id="setting.attribute" :name="setting.attribute" v-model="setting.value" @blur="update(setting.attribute, setting.value)">

                <!-- Field YesNo -->
                <div v-else-if="setting.type === 'YesNo'" class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" :id="setting.attribute" :name="setting.attribute" :checked="setting.value === 'Yes'" @change="toggleYesNo(setting)" />
                    <label class="form-check-label text-white" :for="setting.attribute">
                        {{ setting.value === 'Yes' ? 'Ativado' : 'Desativado' }}
                    </label>
                </div>

                <!-- Field select -->
                <select v-else-if="setting.type === 'select'" class="form-control" v-model="setting.value" @change="update(setting.attribute, setting.value)">
                    <optgroup v-for="(zones, region) in timezones" :key="region" :label="region">
                        <option v-for="tz in zones" :key="tz" :value="tz">{{ tz }}</option>
                    </optgroup>
                </select>

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
                timezones: [],
            }
        },
        methods: {
            loadList() {
                let url = this.urlBase;
                utils.axiosGet(url, this, 'settings');
                utils.cleanFeedbackMessage(this);                    
            },
            toggleYesNo(setting) {
                setting.value = setting.value === "Yes" ? "No" : "Yes";
                this.update(setting.attribute, setting.value);
            },
            update(attribute, value) {
                let data = {
                    [attribute]: value
                };
                let url = this.urlBase;
                utils.axiosPatch(url, data, this);
            },
            loadTimezones() {
                let url = this.urlBase + '/timezones';
                utils.axiosGet(url, this, 'timezones');
            }
        },
        mounted() {
            this.loadList();
            this.loadTimezones();
        }
    }
</script>
<style scoped>
    h6 {
        margin-bottom: 0.25rem;
    }
</style>
