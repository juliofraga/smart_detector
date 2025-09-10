<template>
    <div class="container">
        <div class="row mt-3">
            <alert-component type="warning" :title="message"></alert-component>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message'],
    }
</script>
