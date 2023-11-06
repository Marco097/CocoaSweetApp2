<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ listTitle }}</h3>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" v-model="mostrarReservas">
                            <label class="form-check-label" for="inlineRadio1">Reserva</label>
                        </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" v-model="mostrarPedidos">
                            <label class="form-check-label" for="inlineRadio2">Pedidos</label>
                        </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo" v-model="mostrarDevoluciones">
                            <label class="form-check-label" for="inlineRadio3">Cancelados</label>
                        </div>
                    </div>
  
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="detalleModalLabel">Detalle de la Reserva</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!--definiendo el cuerpo del modal-->
        <div class="row">
            <div class="form-group col-12">
          <div class="col-6">
            <label for="" v-if="pedido != null">No reserva:&nbsp;{{ pedido.correlativo }}</label>
          </div>
          <div class="col-6">
            <label for="" v-if="pedido != null">Cliente:&nbsp;{{ pedido.user.name }}</label>
          </div>
          <div class="row">
            <div class="col-6">
                <label for="" v-if="pedido != null" >Fecha Entrega:&nbsp;{{ pedido.fecha_entrega }}</label>
            </div>
            
           
          </div>
        </div>
        <table class="table table-bordered">
                            <thead>
                                <th>Nombre</th>
                                <th>Relleno</th>                         
                                <th>Catalogo</th>
                                <th>Cliente</th>
                                <th>Precio</th>
                                <th></th>
                            </thead>
                            <tbody>
                              <tr v-if="pedido != null" v-for="item  in pedido.detallePedido" :key="item.id">
                                  <td>{{ item.producto.nombre }}</td>
                                  <td>{{ item.producto.relleno }}</td>
                                  <td>{{ item.producto.catalogo }}</td>
                                  <td>{{ item.total }}</td>
                                  <td>{{ item.user.name }}</td>
                                  <td>
                                    <button class="btn btn-success btn-sm" @click="$event =>showDialog(item)" >Ver Detalle</button>
                                      &nbsp;
                                      <button class="btn btn-success btn-sm">Realizar Pedido</button>
                                      &nbsp;
                                      <button class="btn btn-info btn-sm">Devolver Alquiler</button>
                                      &nbsp;
                                      <button class="btn btn-danger btn-sm">Anular Reserva</button>
                                  </td>
                              </tr>
                          </tbody>

                        </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
       <!--<button type="button" class="btn btn-primary" @click="saveOrUpdate"> {{ btnTitle }}</button>-->
      </div>
    </div>
  </div>
</div>

  </template>
  
  <script>
  export default {
      data() {
          return{
              estado:"P",
              mostrarReservas:false,
              mostrarPedidos:false,
              mostrarDevoluciones:false,
              listTitle:"Gestion de Reservas De Ice Roll y Paletas",
              pedidos:[],
              editedPedido:-1,
              pedido: null
          }
      },
        watch:{
            mostrarReservas(){
                let me = this;
                if(me.mostrarReservas){
                    me.estado = 'P';
                    me.listTitle = "Gestion de Reservas de Productos";
                }
                me.fetchPedidos();
            },
            mostrarPedidos(){
                let me = this;
                if(me.mostrarPedidos){
                    me.estado = 'E';
                    me.listTitle = "Gestion de Pedido de Productos";
                }
                me.fetchPedidos();
            },
            mostrarDevoluciones(){
                let me = this;
                if(me.mostrarDevoluciones){
                    me.estado = 'C';
                    me.listTitle = "Gestion de Cancelados de Productos";
                }
                me.fetchPedidos();
            }
        },
        methods:{
            async fetchPedidos(){
                let me = this;
                await this.axios.get(`/pedidos/$(me.estado)`)
                .then(response =>{
                    console.log(response.data);
                    if(Object.keys(response.data).length==0)
                    {
                        me.pedidos = [];
                    }else
                    {
                        me.pedidos = response.data;
                    }
                    
                }).catch(errors =>{
                    console.log(errors);
                });
            },
            showDialog(item){
                $('#detalleModal').modal('show');
                this.pedido = item;
            },
        },
        mounted(){
            let me = this;
            me.fetchPedidos();
        }
    }
  </script>