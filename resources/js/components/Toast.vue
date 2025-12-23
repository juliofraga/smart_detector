<template>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div ref="toastEl" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div :class="styleHeader">
                <strong class="me-auto">{{ title }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div :class="styleBody" v-if="this.type == 'danger' && details.message">
                <p>{{ details.message }}</p>
                <ul v-if="details.data">
                    <li v-for="(d, key) in details.data" :key="key">
                        {{ d[0] }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import Toast from 'bootstrap/js/dist/toast'

    export default {
        props: {
            type: {
                type: String,
                default: 'success'
            },
            title: String,
            details: Object,
            delay: {
                type: Number,
                default: 10000
            }
        },

        data() {
            return {
                toastInstance: null
            }
        },

        computed: {
            styleHeader() {
                return `toast-header bg-${this.type} text-white`
            },
            styleBody() {
                return `toast-body bg-${this.type} text-white`
            }
        },

        mounted() {
            this.toastInstance = new Toast(this.$refs.toastEl, {
                autohide: true,
                delay: this.delay
            })
        },

        methods: {
            show() {
                this.toastInstance.show()
            }
        }
    }
</script>
