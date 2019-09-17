<template>
    <div>
        <a-modal v-model="visible" :title="eventTitle" onOk="handleOk" :closable="false">
            <template slot="footer">
                <a-button key="back" @click="closeModal">Cerrar</a-button>
                <a-button key="submit" type="primary" :loading="loading" @click="addEvent">
                    Crear Evento
                </a-button>
            </template>

            <a-form-item
                label="Tipo de evento"
                :label-col="{ span: 6 }"
                :wrapper-col="{ span: 12 }"
            >
                <a-radio-group defaultValue="warning" buttonStyle="solid" v-model="type">
                    <a-radio-button value="success">success</a-radio-button>
                    <a-radio-button value="processing">processing</a-radio-button>
                    <a-radio-button value="default">default</a-radio-button>
                    <a-radio-button value="error">error</a-radio-button>
                    <a-radio-button value="warning">warning</a-radio-button>
                </a-radio-group>
            </a-form-item>

            <a-form-item
                label="Hora del evento"
                :label-col="{ span: 6 }"
                :wrapper-col="{ span: 12 }"
            >
                <a-time-picker @change="onHourChange" :minuteStep="15" :value="hour" :defaultValue="moment('10:00', 'HH:mm')" format="HH:mm" />
            </a-form-item>

            <a-form-item
                label="Información"
                :label-col="{ span: 6 }"
                :wrapper-col="{ span: 12 }"
            >
                <a-textarea v-model="content" :autosize="{ minRows: 2, maxRows: 3 }"></a-textarea>
            </a-form-item>

        </a-modal>
    </div>
</template>
<script>
    import * as moment from 'moment';
    export default {
        name: 'EventsModal',
        props: {
            visible: {
                type: Boolean,
                required: true
            },
            selectedDate: {
                type: Object,
                required: true
            }
        },
        data () {
            return {
                loading: false,
                type: 'success',
                hour: moment().hour(10).minute(0),
                content: ''
            }
        },
        computed: {
            eventTitle () {
                return `Evento para el día ${this.selectedDate.format('DD/MM/Y')}`;
            }
        },
        methods: {
            moment,
            addEvent () {
                this.loading = true;
                setTimeout(() => {
                    this.$emit('addNewEvent', {
                        type: this.type,
                        hour: this.hour.format("HH:mm"),
                        content: this.content,
                        day: this.selectedDate.date(),
                        month: this.selectedDate.month(),
                        year: this.selectedDate.year(),
                    })
                    Object.assign(this.$data, this.$options.data());
                }, 3000)
            },
            closeModal () {
                this.$emit('closeModal')
            },
            onHourChange (hour) {
                this.hour = hour;
            }
        }
    }
</script>
