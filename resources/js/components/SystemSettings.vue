<template>
    <div>
        <h3 class="text-white">{{ translations.system_settings }}</h3>
        <div class="card p-3 mt-3">
            <div v-for="(setting, index) in settings" :key="index" class="mb-4 p-3 border border-secondary rounded" v-if="setting.active == 1">
                <h5 class="text-white">{{ setting.title }}</h5>
                <p class="text-muted medium mb-2">{{ setting.description }}</p>

                <!-- Field text -->
                <input v-if="setting.type === 'text'" type="text" class="form-control" :id="setting.attribute" :name="setting.attribute" v-model="setting.value" @blur="update(setting.attribute, setting.value)">

                <!-- Field YesNo -->
                <div v-else-if="setting.type === 'YesNo'" class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" :id="setting.attribute" :name="setting.attribute" :checked="setting.value === 'Yes'" @change="toggleYesNo(setting)" />
                    <label class="form-check-label text-white" :for="setting.attribute">
                        {{ setting.value === 'Yes' ? translations.activated : translations.deactivated }}
                    </label>
                </div>

                <!-- Field select -->
                <select v-else-if="setting.type === 'select'" class="form-control" v-model="setting.value" @change="update(setting.attribute, setting.value)">
                    <optgroup v-if="setting.attribute === 'timezone_selected'" v-for="(zones, region) in timezones" :key="region" :label="region">
                        <option v-for="tz in zones" :key="tz" :value="tz">{{ tz }}</option>
                    </optgroup>
                    <option v-if="setting.attribute === 'llm_standard'" v-for="llm in llms" :key="llm.id" :value="llm.id">{{ llm.name }} - {{ llm.provider }} ({{ llm.model_id }})</option>
                </select>

                <!-- Field picklist -->
                <select v-else-if="setting.type === 'picklist'" class="form-control" v-model="setting.value" @change="update(setting.attribute, setting.value)">
                    <option v-if="setting.picklist === 'languages'" v-for="language in languages" :key="language.id" :value="language.lang">
                        {{ language.description }} ({{ language.lang }})
                    </option>
                </select>

                <!-- Field textarea -->
                <textarea v-if="setting.type === 'textarea'" class="form-control" :id="setting.attribute" :name="setting.attribute" v-model="setting.value" @blur="update(setting.attribute, setting.value)" rows="5"></textarea>
            </div>
        </div>
        <toast-component ref="toastError" type="danger" :details="feedbackMessage" :title="feedbackTitle" v-show="status == 'error'"></toast-component>
        <toast-component ref="toastSuccess" type="success" :details="feedbackMessage" :title="feedbackTitle" v-show="status == 'success'"></toast-component>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                settings: {data: {}},
                urlBase: utils.API_URL + '/api/v1/system-settings',
                urlBaseLlms: utils.API_URL + '/api/v1/llm',
                urlBaseLanguages: utils.API_URL + '/api/v1/languages',
                status: '',
                feedbackMessage: {},
                feedbackTitle: '',
                loaded: false,
                timezones: [],
                llms: [],
                translations: {},
                languages: []
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
                utils.axiosPatch(url, data, this, attribute);
            },
            loadTimezones() {
                let url = this.urlBase + '/timezones';
                utils.axiosGet(url, this, 'timezones');
            },
            loadLlms() {
                let url = this.urlBaseLlms + '/identifiers';
                utils.axiosGet(url, this, 'llms');
            },
            loadLanguages() {
                utils.axiosGet(this.urlBaseLanguages, this, 'languages');
            }
        },
        watch: {
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
        mounted() {
            this.loadList();
            this.loadTimezones();
            this.loadLlms();
            this.loadLanguages();
            utils.loadTranslations(this, 'system_settings_domain', 'translations');
        }
    }
</script>
<style scoped>
    h6 {
        margin-bottom: 0.25rem;
    }
</style>
