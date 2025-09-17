<template>
    <div class="row mt-4" v-if="data.data.length > 0">
        <div class="col col-10">
            <nav>
                <ul class="pagination" style="cursor:pointer">
                    <li v-for="l, key in data.links" :key="key" class="page-item" @click="paginate(l)">
                        <div v-if="l.active">
                            <a class="page-link dark-paginantion-active" v-html="l.label" 
                            v-if="
                                key == data.current_page || 
                                key == data.current_page - 1 || 
                                key == data.current_page + 1 || 
                                key == 0 ||
                                (data.current_page == 1 && key == 3) ||
                                key == data.last_page + 1 || 
                                (data.current_page == data.last_page && key == data.last_page - 2)"
                        ></a>
                        </div>
                        <div v-else>
                            <a class="page-link dark-pagination" 
                            v-if="
                                l.url != null && 
                                (key == data.current_page || 
                                key == data.current_page - 1 || 
                                key == data.current_page + 1 || 
                                key == 0 ||
                                (data.current_page == 1 && key == 3) ||
                                key == data.last_page + 1 || 
                                (data.current_page == data.last_page && key == data.last_page - 2))"
                            >{{ l.label | formatNextPrevButton }}</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    import { EventBus } from "./eventBus.js";
    export default {
        props: ['data'],
        methods: {
            paginate(l) {
                EventBus.$emit("paginate", l);
            }
        }
    }
</script>