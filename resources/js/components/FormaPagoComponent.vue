<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Listado de Formas de Pago</h5>
                      </div>
                       <div class="col 6">
                        <button @click="showDialog" class="btn btn-success btn-sm float-end">Nuevo</button>
                       </div>
                    </div>
                    <div class="row">
                      <div class="input-group rounded">
                        <input type="search" v-model="search" @input="buscar" class="form-control rounded" placeholder="Buscar" arial-label="Search" aria-describedby="search-addon">
                      </div>
                    </div>
                  
                      </div>
                    <div class="card-body">
                      <table class="table bordered">
                        <thead>
                          <tr>
                            <th scope="col">Metodo_pago</th>
                          </tr>
                        </thead>
                            <tbody>
                              <tr v-for="item in formas_pagos" :key = "item.id" >
                                  <td>{{ item.nombre }}</td>
                                <td>
                                  <button class="btn btn-primary btn-sm" @click="showDialogEditar(item)">Editar</button>
                                  &nbsp;
                                  <button class="btn btn-danger btn-sm" @click="eliminar(item)">Eliminar</button>
                                </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
  <div class="modal fade" id="forma_PagoModal" tabindex="-1" aria-labelledby="forma_PagoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="forma_PagoModalLabel">{{ formTitle }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!--definiendo el cuerpo del modal-->
          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">Metodo de pago</label>
              <input type="text" class="form-control" v-model="sabor.nombre">
              <span class="text-danger" v-show="saborErrors.nombre">Nombre del metodo de pago es requerido</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" @click="saveOrUpdate"> {{ btnTitle }}</button>
        </div>
      </div>
    </div>
  </div>
  </template>
  
  <script>
    export default{
          data(){
              return{
                  FormasPagos:[],
                  formaPago:{
                    id:null,
                    metodo_pago: ""
                  },
                  editedFormaPago: -1,
                  formaPagoErrors:{
                    metodo_pago:false
                  },
                  filters:[],
                  search: ''
              }
          },
          created: function(){
            this.filters = this.formas_pagos;
          },
          computed:{
        formTitle(){
            return this.formaPago.id == null ? "Agregar metodo de pago" : "Actualizar metodo de pago";
          },
          btnTitle(){
          return this.formaPago.id == null ? "Guardar" : "Actualizar";
          },
        items()
          {
            return this.formas_pagos.filter(item =>{
              return item.metodo_pago.toLowerCase().includes(this.seach.toLocaleLowerCase());
            } )
          }
        },
          methods:{
               //para que no haga una peticion directa 
              async fetchSabores(){
                  let me = this;
                  await this.axios.get('/formas_pagos')
                  .then(response =>{
                     me.formas_pagos = response.data;
                  })
              },
              // para agregar nueva marca
              showDialog(){
                this.formaPago = {
                  id: null,
                  metodo_pago: ""
                },
                this.formaPagoErrors = {
                  metodo_pago:false
                }
            $('#forma_PagoModal').modal('show');
          },
          hideDialog()
      {
        let me = this;
        setTimeout(() =>{
          me.formaPago = {
            id:null,
            metodo_pago:""
          };
        },300)
        $('#forma_PagoModal').modal('hide');
      },
          //metodo para editar un sabor
          showDialogEditar(formaPago){
            let me = this;
            $('#forma_PagoModal').modal('show');
            me.editedformaPago = me.formas_pagos.indexOf(FormasPagos);
            me.formaPago = Object.assign({}, formaPago);
          },
          //este metodo es una meticion entonces tiene que ser async
        //metodo para guardar o actualizar
        async saveOrUpdate(){

          let me = this;
  me.formaPagoErrors.metodo_pago = false;
  me.formaPago.metodo_pago = me.formaPago.metodo_pago.trim().toLowerCase(); // Convertir a minúsculas y eliminar espacios

  if (!me.formaPago.metodo_pago) {
    me.formaPagoErrors.metodo_pago = true;
    return; // Detener el proceso si el nombre está vacío o solo contiene espacios
  }

  // Verificar si el nombre del sabor ya existe en la lista
  const existingFormaPago = me.formas_pagos.find(item => item.metodo_pago.toLowerCase() === me.formaPago.metodo_pago);
  if (existingFormaPago) {
    me.formaPagoErrors.metodo_pago = true;
    // Mostrar mensaje de error
    this.$swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'La forma de pago ya existe en la lista.',
      timer: 3000,
      timerProgressBar: true,
    });
    return; // Detener el proceso si el nombre ya existe
  }


          //let me = this;         //el de abajo es operrador ternario
          me.formaPago.metodo_pago == ''  ? me.formaPagoErrors.metodo_pago = true :  me.formaPagoErrors.metodo_pago = false
          if(me.formaPago.metodo_pago){     //operador ternario
                        // variable accion sera de agregar (add) y si no que actualice (upd)  
            let accion = me.formaPago.id == null ? "add" : "upd";
            if(accion == "add"){
              //guardar una marca (post en caso de agregar )
              await this.axios.post('/formas_pago', me.formaPago)
              .then(response =>{
                //console.log(response.data);
                if(response.status == 201)
                  {
                    me.verificarAccion(response.data.data,response.status,accion);
                    me.hideDialog();
                  }
                //me.verificarAccion(response.data.data,response.status,accion);
                //me.hideDialog();
              }).catch(errors =>{
                console.log(errors);
              })
             }else{
              //para actualizar una marca, con comias invertidas para poder concatenar algo que es texto con un valor
              await this.axios.put(`/forma_pago/${me.formaPago.id}`, me.formaPago)
              .then(response =>{
                //preguntamos si la peticion se completa
                if(response.status == 202){
                  me.verificarAccion(response.data.data,response.status,accion);
                  me.hideDialog();
                }
              }).catch(errors =>{
                console.log(errors);
              })
            }
          }
        },
          //metodo para borrar
          async eliminar(_formaPago){
            let me = this;
            this.$swal.fire({
              title: 'Seguro/a de eliminar este registro?',
              text: "No podras revertir la accion",
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si',
              cancelButtonText: 'No',
            }).then((result) =>{
              if(result.value){
                me.editedFormaPago = me.formas_pagos.indexOf(formaPago);
                this.axios.delete(`/formas_pagos/${formaPago.id}`)
                .then(response =>{
                  me.verificarAccion(null,response.status,"del");
                }).catch(errors =>{
                  console.log(errors)
                })
              }
            })
          },
          //metodo para que muestre un msj y actualize la tabla cuando inserte una marca nueva  
          verificarAccion(_formaPago, statusCode, accion){
            let me = this;
            const Toast = this.$swal.mixin({
              toast:true,
              position: 'top-right',
              showConfirmButton:false,
              timer:2000,
              timerProgressBar: true
            });
            switch (accion){
              case "add":
                //se agrega al principio del arreglo marcas, la nueva marca
                me.formas_pagos.unshift(formaPago);
                Toast.fire({
                  icon: 'success',
                  title: 'Forma de Pago Registrada con Exito'
                });
                break;
                case "upd":
                  Object.assign(me.formas_pagos[me.editedFormaPago], formaPago);
                  Toast.fire({
                    icon: 'success',
                    'title': 'forma pago se a Actualizado con Exito'
                  });
                  break;
                  case "del":
                  if(statusCode == 200)
                 {
              try{
                me.formas_pagos.splice(me.editedFormaPago,1);
                //se lanza el mensaje final
                Toast.fire({
                  icon: 'success',
                 'title': 'forma de pago se a Eliminado...!!!'
                });
              }catch(error)
              {
                console.log(error);
              }
            }else{
              Toast.fire({
                icon: 'warning',
               'title': 'error al eliminar la forma de pago, intente de nuevo'
              });
            }
                    break;
              }
          },
        },
          mounted(){
            this.fetchSabores();
          },
      }     
  </script>