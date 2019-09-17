<template>
    <div>
        <a-locale-provider :locale="locale">
            <a-calendar @select="addEvent" @panelChange="onPanelChange" :value="selectedDate" :mode="mode">
                <ul class="events" slot="dateCellRender" slot-scope="value">
                    <li v-for="event in getListData(value)" :key="event.content">
                        <a-badge :status="event.type" :text="`${event.hour}: ${event.content}`" />
                    </li>
                </ul>
                <template slot="monthCellRender" slot-scope="value">
                    <div v-if="getMonthData(value)" class="notes-month">
                        <section>{{getMonthData(value)}}</section>
                        <span>Eventos</span>
                    </div>
                </template>
            </a-calendar>
        </a-locale-provider>
        <events-modal @addNewEvent="addNewEvent" @closeModal="closeModal" :visible="visible" :selected-date="selectedDate" />
    </div>
</template>
<script>
    import esES from 'ant-design-vue/lib/locale-provider/es_ES';
    import * as moment from 'moment';
    import EventsModal from "./EventsModal";
    export default {
        name: 'EventsCalendar',
        components: {EventsModal},
        data () {
            return {
                locale: esES,
                selectedDate: moment(),
                visible: false,
                eventsData: [],
                eventsDataCountForYear: [],
                mode: 'month'
            }
        },
        async mounted () {
            //TODO obtener datos del backend
            await this.fetchEvents();
            await this.fetchCountEventsForYear();
        },
        methods: {
            async fetchEvents () {
                try {
                    this.eventsData = [];
                    const data = await fetch(`/events/${this.selectedDate.month()}/${this.selectedDate.year()}`)
                    const json = await data.json();
                    if (json.length) {
                        json.map(event => {
                            if ( ! Array.isArray(this.eventsData[event.day])) {
                                this.eventsData[event.day] = [];
                            }
                            this.eventsData[event.day].push(event);
                        })
                    }
                    setTimeout(() => {
                        this.$forceUpdate();
                    }, 500)
                } catch (e) {
                    this.$notification.error({
                        message: 'Error obteniendo los eventos',
                        description: 'No se han podido obtener los eventos para el mes actual'
                    })
                }
            },
            async fetchCountEventsForYear (date = null) {
                try {
                    this.eventsDataCountForYear = [];
                    const data = await fetch(`/events/${date ? date.year() : this.selectedDate.year()}`)
                    const json = await data.json();
                    if (json.length) {
                        json.map(event => {
                            this.eventsDataCountForYear[event.month] = event.total;
                        })
                    }
                } catch (e) {
                    this.$notification.error({
                        message: 'Error obteniendo los eventos',
                        description: 'No se han podido obtener los eventos para el año actual'
                    })
                }
            },
            getListData(value) {
                let listData;
                if (value.month() === this.selectedDate.month()) {
                    const day = value.date();
                    listData = this.eventsData[day];
                }
                return listData || [];
            },
            getMonthData(value) {
                return this.eventsDataCountForYear[value.month()];
            },
            async addEvent (date) {
                const copySelectedDate = this.selectedDate;
                this.selectedDate = date;
                if (this.mode === 'month') {
                    this.visible = true;
                } else {
                    if (copySelectedDate.month() !== date.month()) {
                        await this.fetchEvents();
                    }
                    this.mode = 'month';
                }
            },
            async onPanelChange (date) {
                let previousRequest = false;
                if (date.year() !== this.selectedDate.year()) {
                    await this.fetchCountEventsForYear(date);
                    this.mode = 'year';
                    previousRequest = true;
                }
                if (date.month() !== this.selectedDate.month()) {
                    this.mode = 'month';
                } else {
                    this.mode = 'year';
                }

                this.selectedDate = date;
                if ( ! previousRequest) {
                    await this.fetchEvents();
                }
            },
            async addNewEvent (data) {
                try {
                    await window.axios.post('/events', data)
                    this.visible = false;
                    await this.fetchCountEventsForYear();
                    await this.fetchEvents();
                    this.$notification.success({
                        message: 'Evento creado satisfactoriamente',
                        description: 'El evento ha sido creado'
                    })
                } catch (e) {
                    this.$notification.error({
                        message: 'Error creando el evento',
                        description: 'Por favor contacte con el administrador'
                    })
                }
            },
            closeModal () {
                this.visible = false;
                this.mode = 'month';
            }
        }
    }
</script>
<style scoped>
    .events {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .events .ant-badge-status {
        overflow: hidden;
        white-space: nowrap;
        width: 100%;
        text-overflow: ellipsis;
        font-size: 12px;
    }
    .notes-month {
        text-align: center;
        font-size: 28px;
    }
    .notes-month section {
        font-size: 28px;
    }
</style>