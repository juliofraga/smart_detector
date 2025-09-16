<template>
    <div class="mt-3" v-if="(Array.isArray(data) && data.length === 0)">
        <alert-component type="warning" title="Não foram encontratos resultados"></alert-component>
    </div>
    <div class="mt-2" v-else>
        <alert-component type="danger" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'error'"></alert-component>
        <alert-component type="success" :details="feedbackMessage" :title="feedbackTitle" v-if="status == 'success'"></alert-component>
        <div class="card p-3 mt-3">
            <h5 class="mb-3">{{ sectionTitle }}</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" v-for="t, key in title" :key="key" v-if="t.type != 'buttonModal' && t.hidden == 'false'">{{ t.title }}</th>
                            <th scope="col" v-for="t, key in title" :key="key" v-if="t.type == 'buttonModal'"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(obj, key) in filteredData" :key="key">
                            <td scope="row" v-for="(value, keyValue) in obj" :key="keyValue" v-if="title[keyValue].hidden == 'false'" style="height:80px;">
                                <span v-if="title[keyValue].type == 'text' && title[keyValue].hidden == 'false' && keyValue != 'profile'" class="text-white">
                                    {{ value }}
                                </span>

                                <span v-if="keyValue == 'profile' && title[keyValue].hidden == 'false'" class="text-white">
                                    {{ value.description }}
                                </span>

                                <span v-if="title[keyValue].type == 'datetime' && title[keyValue].hidden == 'false'" class="text-white">
                                    {{ value | formatDateTime }}
                                </span>

                                <span v-if="title[keyValue].type == 'timestamp' && title[keyValue].hidden == 'false'" class="text-white">
                                    {{ value | formatDateTimeStamp }}
                                </span>

                                <div v-if="classList == 'classification'">
                                    <span v-if="title[keyValue].type == 'badge' && title[keyValue].hidden == 'false' && (value == 'warning')" :class="`badge badge-${value} w-100`">
                                        {{ value }}
                                    </span>

                                    <span v-if="title[keyValue].type == 'badge' && title[keyValue].hidden == 'false' && (value != 'warning')" :class="`badge bg-${value} w-100`">
                                        {{ value }}
                                    </span>
                                </div>
                                <div v-elseif="classList == 'event'">
                                    <span v-if="title[keyValue].type == 'badge' && title[keyValue].hidden == 'false' && (value == 'warning')" :class="`badge badge-${value.visual_style} w-100`">
                                        {{ value.description }}
                                    </span>

                                    <span v-if="title[keyValue].type == 'badge' && title[keyValue].hidden == 'false' && (value != 'warning')" :class="`badge bg-${value.visual_style} w-100`">
                                        {{ value.description }}
                                    </span>
                                </div>

                                <button 
                                    v-if="title[keyValue].type == 'buttonModal' && title[keyValue].hidden == 'false'" 
                                    class="btn btn-sm btn-outline-light w-100" 
                                    data-bs-toggle="modal" 
                                    :data-bs-target="title[keyValue].modalId" 
                                    @click="setStore(obj)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16" v-if="title[keyValue].buttonType == 'edit'">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" v-if="title[keyValue].buttonType == 'view'">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                    {{ title[keyValue].title }}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
            }
        },
        props: ['title', 'data', 'status', 'feedbackTitle', 'feedbackMessage', 'classList', 'sectionTitle'],
        methods: {
            setStore(obj) {
                this.$store.state.item = obj;
            },
        },
        computed: {
            filteredData(){
                if (Array.isArray(this.data)) {
                    let fields = Object.keys(this.title);
                    let filteredData = [];
                    this.data.map((item, key) => {
                        let filteredItem = {};
                        fields.forEach(field => {
                            filteredItem[field] = item[field]
                        });
                        filteredData.push(filteredItem);
                    });
                    return filteredData;
                }
            }
        },
    }
</script>
