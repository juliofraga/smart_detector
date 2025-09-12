<template>
    <div class="modal fade" :id="id" tabindex="-1" aria-hidden="true">
        <div :class="['modal-dialog', options]">
            <div class="modal-content bg-dark border-0 shadow-lg">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-light">{{ title }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot name="conteudo"></slot>
                </div>
                <div class="modal-footer border-secondary">
                    <slot name="rodape"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: String,
            title: String,
            options: {
                type: String,
                default: ''
            }
        }
    }
</script>
