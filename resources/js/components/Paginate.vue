<template>
    <div class="row mt-4" v-if="data.data.length > 0">
        <div class="col col-10" v-if="data.next_page_url != null || data.prev_page_url != null">
            <nav>
                <ul class="pagination" style="cursor:pointer">
                    <li v-for="(l, key) in data.links" :key="key" class="page-item" @click="paginate(l)">
                        <a 
                            :class="l.active ? 'page-link dark-pagination-active' : 'page-link dark-pagination'"
                            v-if="
                                (l.label.includes('Next') && l.url != null) ||
                                (l.label.includes('Previous') && l.url != null) ||
                                l.active ||
                                parseInt(l.label) === data.current_page ||
                                parseInt(l.label) === data.current_page - 1 ||
                                parseInt(l.label) === data.current_page + 1
                            "
                        >
                            {{ l.label | formatNextPrevButton }}
                        </a>
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
