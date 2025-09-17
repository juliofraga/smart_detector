<template>
    <div class="container">
        <modal-component id="modalConfirmDelete" options="modal-dialog-centered modal-sm" title="Você tem certeza?">
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col col-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" @click="showModal('modalUpdate')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>    
                            Não
                        </button>
                    </div>
                    <div class="col col-6">
                        <button type="button" class="btn btn-danger text-white w-100" @click="deleteRecord()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                            </svg>
                            Sim</button>
                    </div>
                </div>
            </template>
            <template v-slot:rodape></template>
        </modal-component>
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    import { EventBus } from "./eventBus.js";
    export default {
        methods: {
            showModal(modal) {
                utils.showModal(modal);
            },
            deleteRecord() {
                EventBus.$emit("deleteRecord");
            }
        },
    }
</script>
