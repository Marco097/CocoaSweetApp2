<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <h5 class="float-start">Listado de pedidos</h5>
                            </div>
                            <div class="col-3">
                                <button @click="showDialog" class="btn btn-success btn-sm float-end">Nuevo</button>
                            </div>
                         
                        </div>
                    </div>
  
                    <div class="card-body">
                        <table class="table bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Direecion envio</th>
                                    <th scope="col">telefono</th>
                                    <th scope="col">total</th>
                                    <th scope="col">costo envio</th>
                                    <th scope="col">fecha entrega</th> 
                                    <th scope="col">estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in pedidos" :key="item.id">
                                    <td>{{ item.direccionEnvio }}</td>
                                    <td>{{ item.telefono}}</td>  
                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="pedidoModal" tabindex="-1" aria-labelledby="pedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-7" id="pedidoModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Definiendo el cuerpo del formulario modal -->
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="direccion">Direccion</label>
                            <input type="text" class="form-control" v-model="pedido.direccionEnvio">
                            <span class="text-danger" v-show="pedidoErrors.direccionEnvio">nombre requerido</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="telefono">telefono</label>
                            <input type="text" class="form-control" v-model="pedido.telefono">
                           
                        </div>
                    </div>                   
                    <div class="row">
  
                    </div>
                    

                    <div class="row">
                        <div class="form-group col-6">
                            <label for="direccion">direccion</label>
                            <input type="text" class="form-control" v-model="pedido.direccionEnvio">
                            <span class="text-danger" v-show="pedidoErrors.direccionEnvio">direccion es requerido</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="telefono">telefono</label>
                            <input type="number" class="form-control" v-model="pedido.telefono">
                            <span class="text-danger" v-show="pedidoErrors.telefono">telefono es requerido</span>
                        </div>
                    </div>
                    <div class="row">
                  
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="saveOrUpdate"> {{ btnTitle }}</button>
                </div>
                </div>                
            </div>
        </div>
  </div>
  </template>
  
  <script>

  export default {
    data() {
        return {
            pedidos: [],
            pedido: {
                id: null,
                direccion_envio: "",
                telefono:null,              
                total: 0,
                costo_Envio:0,
                fecha_entrega:"",
                estado:null,
               

            },
        
        }
    },
    created: function(){
          this.filters = this.pedidos;
        },
    computed:{
        formTitle(){
            return this.pedido.id == null ? "Agregar pedido" : "Actualizar producto";
          },
          btnTitle(){
          return this.pedido.id == null ? "Guardar" : "Actualizar";
          }
        },
    methods: {
        async fetchPedidos() {
            let me = this;
            await this.axios.get('/pedidos')
                .then(response => {
                    me.pedidos = response.data;
                    console.log('Productos:', me.pedidos);
                })
               // me.fetchPedidos();
        },
        showDialog() {
            this.pedido = {
                id: null,
                direccion_envio: "",
                telefono: "",
                total: 0,
                costo_envio: 0,
                fecha_entrega: "",
                estado: null,
               
            }
        },
        showDialogEditar(pedido) {
            let me = this;
            $('#pedidoModal').modal('show');
            me.editedPedido = me.pedidos.indexOf(pedido);
            me.pedido = Object.assign({}, pedido);
         

             // Cargar el sabor y el relleno usando su ID
          //  me.producto.sabor_id = producto.sabor ? producto.sabor.id : null;
            //me.producto.relleno_id = producto.relleno ? producto.relleno.id : null;
        },
        
        
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.pedido = {
                    id: null,
                direccion_envio: "",
                telefono: "",
                total: 0,
                costo_envio: 0,
                fecha_entrega: null,
                estado: null,
                },
             
            }, 300);
            $('#pedidoModal').modal('hide');
        },
        
        async saveOrUpdate() {
            let me = this;
            me.pedido.direccion_envio == '' ? me.pedidoErrors.direccion_envio = true : me.pedidoErrors.direccion_envio = false;
            me.pedido.telefono == null ? me.pedidoErrors.telefono = true : me.pedidoErrors.telefono = false;
            me.pedido.total == null ? me.pedidoErrors.total = false : me.pedidoErrors.telefono = false;
            me.pedido.costo_envio == '' ? me.pedidoErrors.costo_envio = false : me.pedidoErrors.costo_envio = false;
            me.pedido.fecha_entrega == null ? me.pedidoErrors.fecha_entrega = true : me.pedidoErrors.fecha_entrega = false;
            me.pedido.estado == null ? me.pedidoErrors.estado = true : me.pedidoErrors.estado = false;

            if (me.pedido.direccion_envio) {
                
                let accion = me.pedido.id == null ? "add" : "upd";
                
                 me.pedido.direccion_envio = {
                    "id" : me.pedido.direccion_envio
                };
                me.pedido.telefono = {
                    "id" : me.pedido.telefono
                };
                me.pedido.fecha_entrega = {
                    "id" : me.pedido.fecha_entrega
                };
                let formData = new FormData();
                formData.append("direccion_envio", me.pedido.direccion_envio);
                formData.append("telefono", me.pedido.telefono);
                formData.append("total", me.pedido.total);
                formData.append("costo_envio", me.pedido.costo_envio);
                formData.append("fecha_entrega", me.pedido.fecha_entrega);
                

             
        
                //definiendo encabezado de peticion
                let headers = {
                  headers: {
                      "Content-type": "multipart/form-data"
                  }
                };
  
                if (accion == "add") {
                    //peticion para guardar una auto
                    //me.producto.imagen = "none.jpg";
                    formData.append("estado",me.pedido.estado);
                    await this.axios.post('/pedidos', formData, headers)
                        .then(response => {
                            console.log(response.data);
                            if (response.status == 201) {
                                me.verificarAccion(response.data.data, response.status, accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                } else {
                    //peticion para actualizar una marca
                   
                   //formData.append("estado",me.pedido.estado)
                   formData.append("id",me.pedido.id)
                   //peticion par actualizar un auto
                   await this.axios.post(`/pedidos/${me.pedido.id}`, formData, headers)
                    .then(response => {
                            //console.log(response.data);
                            if (response.status == 202) {
                                me.verificarAccion(response.data.data, response.status, accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
  
                } 
            }
        },
        async eliminar(producto) {
            let me = this;
            this.$swal.fire({
                title: 'Seguro de eliminar este registro?',
                text: "No podrás revertir esta accion",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    me.editedProducto = me.productos.indexOf(producto);
                    this.axios.delete(`/productos/${producto.id}`)
                        .then(response => {
                            me.verificarAccion(null, response.status, "del");
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            })
        },
        verificarAccion(pedido, statusCode, accion) {
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 2000,
                tinerProgressBar: true
            });
            switch (accion) {
                case "add":
                    //agregamos al principio del arreglo producto, la nueva producto
                    //me.auto.unshift(auto);
                    me.fetchPedidos();
                    Toast.fire({
                        icon: 'success',
                        'title': 'Producto registrada con exito...!'
                    });
                    break;
                case "upd":
                    // Object.assign(me.productos[me.editedProducto], producto); 
                    Toast.fire({
                        icon: 'success',
                        'title': 'Producto actualizada con exito...!'
                    });
                    break;
                case "del":
                    if (statusCode == 205) {
                        me.pedidos.splice(this.editedPedido, 1);
                        Toast.fire({
                            icon: 'success',
                            'title': 'Producto eliminada con exito...!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'warning',
                            'title': 'Ocurrió un error, intente de nuevo...!'
                        });
                    }
                    break;
            }
        },
  
  loadImage(file)
  {
      let reader = new FileReader();
      reader.onload = (e) => {
          this.imagePreview = e.target.result;
      }
      reader.readAsDataURL(file);
  } 
    },
    mounted() {
        // this.$swal('Welcome to RentasCars!!!');
        this.fetchPedidos();
      
    }
  }
  </script>