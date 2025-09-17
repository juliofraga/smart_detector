<template>
    <div class="container">
        <modal-component id="modalConfirmDelete" options="modal-dialog-centered modal-sm" title="Você tem certeza?">
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col col-6">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal" @click="showModal('modalUpdate')">Não</button>
                    </div>
                    <div class="col col-6">
                        <button type="button" class="btn btn-danger text-white w-100" @click="deleteRecord()">Sim</button>
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
