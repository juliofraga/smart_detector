<template>
    <div>
        <h3 class="text-white">{{ title }}</h3>
        <div class="row mt-4">
            <div class="col-sm-3" v-if="buttons.search.show">
                <div class="form-floating">
                    <input type="text" class="form-control" id="buscar" name="buscar" :placeholder="placeholder" v-model="searchFilter">
                    <label class="form-label">{{ placeholder }}</label>
                </div>
            </div>

            <!-- BOTÃO BUSCAR  -->
            <div v-if="buttons.search.show"  class="col-sm-2 mt-1">
                <button class="w-100 btn btn-info btn-lg" @click="search()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                    Buscar
                </button>
            </div>

            <!-- BOTÃO LIMPAR  -->
            <div v-if="buttons.clear.show" class="col-sm-2 mt-1">
                <button class="w-100 btn btn-warning btn-lg" @click="clear()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                    Limpar
                </button>
            </div>

            <!-- BOTÃO ADICIONAR  -->
            <div v-if="buttons.add.show" class="col-sm-2 mt-1">
                <button class="w-100 btn btn-secondary btn-lg" data-bs-toggle="modal" :data-bs-target="buttons.add.modalId">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16" v-if="classSearch == 'user'">
                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-database-add" viewBox="0 0 16 16" v-if="classSearch == 'classification'">
                        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
                        <path d="M12.096 6.223A5 5 0 0 0 13 5.698V7c0 .289-.213.654-.753 1.007a4.5 4.5 0 0 1 1.753.25V4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-.813-.927Q8.378 15 8 15c-1.464 0-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13h.027a4.6 4.6 0 0 1 0-1H8c-1.464 0-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10q.393 0 .774-.024a4.5 4.5 0 0 1 1.102-1.132C9.298 8.944 8.666 9 8 9c-1.464 0-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777M3 4c0-.374.356-.875 1.318-1.313C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4"/>
                    </svg>
                    Adicionar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    export default {
        data() {
            return {
                searchFilter: ''
            }
        },
        methods: {
            add() {
                
            },
            search() {
                let filter = '';
                let fields = this.buttons.search.fields;
                let paginate = '';
                let urlFilter = '';
                fields.forEach((field, key) => {
                    if (filter != ''){
                        filter += ';';
                    }
                    filter += field + ':like:%' + this.searchFilter+ '%'
                });
                if (filter) {
                    paginate = 'page=1';
                    urlFilter = `&filter=${encodeURIComponent(filter)}`;
                    EventBus.$emit("setUrlFilter", urlFilter);
                } else {
                    urlFilter = '';
                }
                if (this.classSearch === 'user') {
                    EventBus.$emit("loadList");
                } else if (this.classSearch === 'classification') {
                    EventBus.$emit("loadList");
                } else if (this.classSearch === 'type') {
                    EventBus.$emit("loadList");
                } else if (this.classSearch === 'event') {
                    EventBus.$emit("loadEventList");
                }
            },
            clear() {
                EventBus.$emit("setUrlFilter", '');
                if (this.classSearch === 'user') {
                    EventBus.$emit("loadList");
                } else if (this.classSearch === 'classification') {
                    EventBus.$emit("loadList");
                } else if (this.classSearch === 'type') {
                    EventBus.$emit("loadList");
                } else if (this.classSearch === 'event') {
                    EventBus.$emit("loadEventList");
                }
                this.searchFilter = '';
            },
        },
        props: ['title', 'buttons', 'placeholder', 'classSearch'],
    }
</script>
