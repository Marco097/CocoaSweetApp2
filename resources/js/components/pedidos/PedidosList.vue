<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-15">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ listTitle }}</h3>
                        <div class="form-group">
                            <input type="text" v-model="searchCorrelativo" placeholder="Correlativo">
                            <input type="text" v-model="searchFechaEntrega" placeholder="Fecha de Entrega">
                            <input type="text" v-model="searchNombreUsuario" placeholder="Nombre de Usuario">
                            <button @click="fetchPedidos">Buscar</button> 
                      </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" v-model="mostrarReservas">
                            <label class="form-check-label" for="inlineRadio1">Reserva</label>
                        </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" v-model="mostrarExpress">
                            <label class="form-check-label" for="inlineRadio2">Pedidos Express</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" v-model="mostrarAgendados">
                            <label class="form-check-label" for="inlineRadio3">Pedidos Agendados</label>
                        </div>
                    </div>
  
                   <div class="card-body">
                         <table class="table table-bordered">
                            <thead>
                                <th>Correlativo</th>
                                <th>Direccion Envio</th>
                                <th>Fecha Entrega</th>
                                <th>Hora entrega</th>
                                <th>Telefono</th>                         
                                <th>Costo Envio</th>
                                <th>Total</th>
                                <th>Cliente</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in pedidos" :key="item.id">
                                    <td>{{ item.correlativo }}</td>
                                    <td>{{ item.direccion_envio }}</td>
                                    <td>{{ item.fecha_entrega }}</td>
                                    <td>{{ item.hora }}</td>
                                    <td>{{ item.telefono }}</td>
                                    <td>{{ item.costo_envio }}</td>
                                    <td>{{ item.total }}</td>
                                    <td>{{ item.user.name }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" @click="showDialog(item)">Ver detalle</button>
                                        &nbsp;
                                        <button v-if="item.estado !== 'E'" class="btn btn-success btn-sm" @click="cambiarEstadoPedido(item.id, 'E')">Express</button>
                                        &nbsp;
                                        <button v-if="item.estado !== 'A'" class="btn btn-info btn-sm" @click="cambiarEstadoPedido(item.id, 'A')">Agendados</button>
                                        &nbsp;
                                        <button class="btn btn-danger btn-sm" @click="cancelarPedido(item.id, 'C')">Anular Pedido</button>
                                    </td>
                                </tr>
                        </tbody>
                </table>
             </div>
          </div>
       </div>
    </div>
  </div>
  <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="detalleModalLabel">Detalle del Pedido</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Definiendo el cuerpo del modal-->
            <div class="row">
                <div class="form-group col-12">
                    <div class="col-6">
                        <label for="" v-if="pedido !=null">N° Pedido:&nbsp;{{ pedido.correlativo }}</label>
                    </div>
                        <div class="col-6">
                            <label for="" v-if="pedido !=null">Cliente:&nbsp;{{ pedido.user.name }}</label>
                        </div>
                        <div class="row">
                        <div class="col-6">
                            <label for="" v-if="pedido !=null">Fecha Entrega:&nbsp;{{ pedido.fecha_entrega }}</label>
                        </div>
                        <div class="col-6">
                            <label for="" v-if="pedido !=null">Hora Entrega:&nbsp;{{ pedido.hora }}</label>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <th>Nombre</th>
                        <th>Relleno</th>
                        <th>Catalogo</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Costo Envio</th>
                    </thead>
                    <tbody>
                        <tr v-if="pedido != null" v-for="item in pedido.detallePedidos" :key="item.id">
                            <td>{{ item.producto.nombre }}</td>
                            <td>{{ item.producto.relleno.nombre }}</td>
                            <td>{{ item.producto.catalogo.nombre }}</td>
                            <td>{{ item.cantidad }}</td>
                            <td class="text-center">{{ pedido.total }}</td>
                            <td class="text-center">{{ pedido.costo_envio }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
    </div>
 </template>
  
  <script>
    import Swal from 'sweetalert2';
    export default {
      data() {
          return{
              estado:"R",
              mostrarReservas:false,
              mostrarExpress:false,
              mostrarAgendados:false,
              searchCorrelativo: '',
              searchFechaEntrega:'',
              searchNombreUsuario:'',
              listTitle:"Gestion de Reservas de Pedidos",
              pedidos:[],
              pedido: null
          }
      },
        watch:{
            searchCorrelativo() {
                this.fetchPedidos();
            },
            searchFechaEntrega(){
                this.fetchPedidos();
            },
            searchNombreUsuario() {
                this.fetchPedidos();
            },
            mostrarReservas(){
                let me = this;
                if(me.mostrarReservas){
                    me.estado = 'R';
                    me.listTitle = "Gestion de Reservas de Pedidos";
                }
                me.fetchPedidos();
            },

            mostrarExpress(){
                let me = this;
                if(me.mostrarExpress){
                    me.estado = 'E';
                    me.listTitle = "Gestion de Reservas de Pedidos Express";
                }
                me.fetchPedidos();
            },
            mostrarAgendados(){
                let me = this;
                if(me.mostrarAgendados){
                    me.estado = 'A';
                    me.listTitle = "Gestion de Reservas de Pedidos Agendados";
                }
                me.fetchPedidos();
            }
        },
        methods:{
            async fetchPedidos(){
                let me = this;
                await this.axios.get(`/pedidos/${me.estado}`)
                .then(response =>{
                    console.log(response.data);
                    if(Object.keys(response.data).length==0){
                        me.pedidos = [];
                    }else{
                        me.pedidos = response.data.filter(pedido => {
                        const correlativoMatches = pedido.correlativo.toLowerCase().includes(me.searchCorrelativo.toLowerCase());
                        const fechaEntregaMatches = pedido.fecha_entrega.toLowerCase().includes(me.searchFechaEntrega.toLowerCase());
                        const nombreUsuarioMatches = pedido.user.name.toLowerCase().includes(me.searchNombreUsuario.toLowerCase());

                        // Puedes ajustar la lógica para que se ajuste a tus necesidades específicas.
                        // En este ejemplo, estoy usando AND lógico, es decir, todos los criterios deben coincidir.

                        return correlativoMatches && fechaEntregaMatches && nombreUsuarioMatches;
                    });
                    }
                }).catch(errors=>{
                    console.log(errors);
                });
            },
           async cambiarEstadoPedido(id, nuevoEstado) {
             try {
                await this.axios.put(`/pedidos/${id}/change`, { nuevoEstado });
                Swal.fire({
                 icon: 'success',
                 title: `Pedido ha sido cambiado ${nuevoEstado}`,
                 text: 'El pedido se paso de estado correctamente.',
                 confirmButtonText: 'OK'
                 }).then(() => {
                 // Recargar la página
                 window.location.reload();
                 });
            } catch (error) {
            console.error("Error en el componente al cambiar el estado:", error);
             // Muestra una alerta de error si la cancelación falla
             Swal.fire({
               icon: 'error',
               title: 'Error',
               text: 'Hubo un problema al cambiar el estado del pedido.',
           });
          }
             },
     async cancelarPedido(id) {
    try {
      await this.axios.put(`/pedidos/${id}/cancelar`);
      Swal.fire({
        title: 'Éxito',
        text: 'El pedido se eliminó correctamente',
        icon: 'success',
        confirmButtonText: 'OK'
        }).then(() => {
       // Recargar la página
       window.location.reload();
      });
       } catch (error) {
      console.error("Error al cancelar el pedido:", error);
      Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Hubo un problema al eliminar el pedido.',
        });
       }
    },
            showDialog(item){
                console.log(item);

                $('#detalleModal').modal('show');
                //me.editedAlquiler = me.alquileres.indexOf(alquiler);
                this.pedido = item;
            }
        },
        mounted(){
            let me = this;
            me.fetchPedidos();
        }
    }
  </script>