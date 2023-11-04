<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div id="show">
                <div v-show="showDiv">
                    <div class="row">
                        <div class="col">
                            <label for="">Cliente</label> &nbsp;{{ user.name }}
                        </div>
                        <div class="col">
                            <label for="">Fecha Alquiler</label>&nbsp;{{
                                reservaForm.fechaAlquiler
                            }}
                        </div>
                    </div>
                    <h4>Detalle de la Reserva</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>año</th>
                                <th>Precio Diario</th>
                                <th>Dias</th>
                                <th>SubTotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="( item, index ) in reservaForm.detallePedido" :key="index">
                                <td>{{ item.auto.placa }}</td>
                                <td>{{ item.auto.marca.nombre }}</td>
                                <td>{{ item.auto.modelo.nombre }}</td>
                                <td>{{ item.auto.anio }}</td>
                                <td>{{ item.auto.precio_alquiler }}</td>
                                <td>{{ diasAlquiler }}</td>
                                <td>
                                    {{
                                        item.auto.precio_alquiler * diasAlquiler
                                    }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" @click="removeItem(index)">
                                        <i class="fa fa-trash" aria-hidden="false"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <b>Total Alquiler</b>
                                </td>
                                <td>{{ total }}</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <button type="" class="btn btn-primary" @click="saveReserva()"
                                        :disabled="reservaForm.detalleAlquiler.length < 1">Confirmar Reserva</button>
                                </td>
                                <td>{{ total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
</template>

<script>
export default {
    props: ["user"],
    data() {
        return {
            autos: [],
            reservaForm: {
                id: null,
                correlativo: "",
                fechaReserva: new Date(Date.now() - new Date().getTimezoneOffset() * 60000),
                fechaAlquiler: new Date(Date.now() - new Date().getTimezoneOffset() * 60000),
                fechaDevolucion: new Date(
                    Date.now() - new Date().getTimezoneOffset() * 60000
                ),
                total: new Number("0").toFixed(2),
                estado: "R",
                user: null,
                detalleAlquiler: [],
            },
            diasAlquiler: 0,
            search: "",
            tipos: [
                { text: "Sedan", value: "S" },
                { text: "Camioneta", value: "C" },
                { text: "Pickup", value: "P" },
                { text: "Limosina", value: "L" },
            ],
            marcas: [],
            modelos: [],
            indexAuto: -1,
            disableButton: false,
            showDiv: false,
        }
    },
    computed: {
        total() {
            var totalRenta = 0;
            this.reservaForm.detalleAlquiler.forEach((element) => {
                totalRenta += element.auto.precio_alquiler * this.diasAlquiler;
            });
            this.reservaForm.total = totalRenta;
            return totalRenta;
        },
        btnReservTitle() {
            return this.showDiv == false ? "Ver Reserva" : "Ocultar Reserva";
        },
    },

    methods: {
        async fetchAutos() {
            let me = this;
            await this.axios.get("/autos-reservas").then((response) => {
                console.log(response.data);
                me.autos = response.data;
            });
        },
        showDialog() {
            $("#reservaModal").modal("show");
        },
        calcDias() {
            const fecha1 = new Date(this.reservaForm.fechaAlquiler);
            const fecha2 = new Date(this.reservaForm.fechaDevolucion);
            const diferenciaEnTiempo = fecha2.getTime() - fecha1.getTime();
            const diasDiferencia = diferenciaEnTiempo / (1000 * 60 * 60 * 24);
            this.diasAlquiler = Math.round(diasDiferencia + 1);
        },
        removeItem(i) {
            this.reservaForm.detalleAlquiler.splice(i, 1);
        },
        addToReserva(item, index) {
            let me = this;
            me.reservaForm.detalleAlquiler.push({
                id: null,
                auto: item,
                diasAlquiler: me.diasAlquiler
            });
            // console.log(me.reservaForm.detalleAlquiler);
        },
        async saveReserva() {
            let me = this;
            if (me.reservaForm.fechaAlquiler.length > 0 && me.reservaForm.detalleAlquiler.length > 0) {
                //seteando datos faltantes para la renta
                me.reservaForm.user = this.user;
                var f = new Date();
                me.reservaForm.fechaReserva = f.getFullYear() + "-" + f.getMonth() + "-" + f.getDate();
                await this.axios.post(`/alquileres`, me.reservaForm)
                    .then(response => {
                        if (response.status == 201) {
                            this.$swal.fire("success", "Su reserva se a registrado con exito, pronto nos comunicaremos");
                            me.reservaForm.detalleAlquiler = [];
                            me.showDiv = false;
                        }
                    }).catch(errors => {
                        console.log(errors);
                    })
            } else {
                this.$swal.fire("warnig", "Complete los datos de la reserva");
            }
        },
    },
    mounted() {
        // this.$swal('Welcome to RentasCars!!!');
        this.fetchAutos();
        //this.fetchMarcas();
        //this.fetchModelos();
        console.log(this.user);
    },
};
</script>
