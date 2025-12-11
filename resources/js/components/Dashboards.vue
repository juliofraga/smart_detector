<template>
    <div class="container">
        <h3 class="text-white">Dashboards</h3>
        <div class="row">
            <div class="row">
                <div class="col-sm-2 mt-2">
                    <div class="form-floating">
                        <input type="datetime-local" class="form-control" id="startdate" name="startdate" placeholder="Data inicial" v-model="startDate">
                        <label class="form-label">Data inicial</label>
                    </div>
                </div>
                <div class="col-sm-2 mt-2">
                    <div class="form-floating">
                        <input type="datetime-local" class="form-control" id="enddate" name="enddate" v-model="endDate">
                        <label class="form-label">Data final</label>
                    </div>
                </div>
                <div class="col-sm-2 mt-2">
                    <div class="form-floating">
                        <select class="form-control" id="ids" name="ids" v-model="ids">
                            <option value="">Todos</option>
                            <option value="1">ids 1</option>
                            <option value="2">ids 2</option>
                        </select>
                        <label class="form-label">IDS</label>
                    </div>
                </div>
                <div class="col-sm-3 mt-2">
                    <button class="w-100 btn btn-info btn-lg" @click="getData()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                        Buscar
                    </button>
                </div>
                <div class="col-sm-3 mt-2">
                    <button class="w-100 btn btn-warning btn-lg" @click="resetValues()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                        Limpar
                    </button>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card text-center p-3">
                    <h5>Total de Eventos</h5>
                    <span class="fs-3 fw-bold text-white">{{ totals.totalEvents }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3">
                    <h5>Intrusões</h5>
                    <span class="fs-3 fw-bold text-danger">{{ totals.totalIntrusions }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3">
                    <h5>Normais</h5>
                    <span class="fs-3 fw-bold text-success">{{ totals.totalNormal }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <overview-dashboard-component></overview-dashboard-component>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-sm-6">
                <severity-dashboard-component></severity-dashboard-component>
            </div>
            <div class="col-sm-6">
                <categories-dashboard-component></categories-dashboard-component>
            </div>
        </div>
        
        
    </div>
</template>

<script>
    import * as utils from '../utils/functions';
    export default {
        data() {
            return {
                data: {data: {}},
                urlBaseEvent: utils.API_URL + '/api/v1/event',
                startDate: utils.getDateTimeOneWeekAgo(),
                endDate: utils.getCurrentDateTime(),
                ids: '',
                totals: {
                    totalEvents: 0,
                    totalIntrusions: 0,
                    totalNormal: 0
                },
            };
        },
        methods: {
            getData() {
                let url = this.urlBaseEvent + '/get/dashboards?from=' + this.startDate + '&to=' + this.endDate + '&ids=' + this.ids;
                utils.axiosGet(url, this, 'data');
            },
            resetValues() {
                this.startDate = utils.getDateTimeOneWeekAgo();
                this.endDate = utils.getCurrentDateTime();
                this.ids = '';
                this.totals.totalEvents = 0;
                this.totals.totalIntrusions = 0;
                this.totals.totalNormal = 0;
            }
        },
        mounted() {
            
        }
    }
</script>
