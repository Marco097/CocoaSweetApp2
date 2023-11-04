<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Banco</h5>
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
                            <th scope="col">Banco</th>
                          </tr>
                        </thead>
                            <tbody>
                              <tr v-for="item in bancos" :key = "item.id" >
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
  <div class="modal fade" id="bancoModal" tabindex="-1" aria-labelledby="bancoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="bancoModalLabel">{{ formTitle }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!--definiendo el cuerpo del modal-->
          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control" v-model="banco.nombre">
              <span class="text-danger" v-show="bancoErrors.nombre">Nombre del banco es requerido</span>
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
                  bancos:[],
                  banco:{
                    id:null,
                    nombre: ""
                  },
                  editedBanco: -1,
                  bancoErrors:{
                    nombre:false
                  },
                  filters:[],
                  search: ''
              }
          },
          created: function(){
            this.filters = this.bancos;
          },
          computed:{
        formTitle(){
            return this.banco.id == null ? "Agregar banco" : "Actualizar banco";
          },
          btnTitle(){
          return this.banco.id == null ? "Guardar" : "Actualizar";
          },
        items()
          {
            return this.bancos.filter(item =>{
              return item.nombre.toLowerCase().includes(this.seach.toLocaleLowerCase());
            } )
          }
        },
          methods:{
               //para que no haga una peticion directa 
              async fetchBancos(){
                  let me = this;
                  await this.axios.get('/bancos')
                  .then(response =>{
                     me.bancos = response.data;
                  })
              },
              // para agregar nueva marca
              showDialog(){
                this.banco = {
                  id: null,
                  nombre: ""
                },
                this.bancoErrors = {
                  nombre:false
                }
            $('#bancoModal').modal('show');
          },
          hideDialog()
      {
        let me = this;
        setTimeout(() =>{
          me.banco = {
            id:null,
            nombre:""
          };
        },300)
        $('#bancoModal').modal('hide');
      },
          //metodo para editar un sabor
          showDialogEditar(banco){
            let me = this;
            $('#bancoModal').modal('show');
            me.editedBanco = me.bancos.indexOf(banco);
            me.banco = Object.assign({}, banco);
          },
          //este metodo es una meticion entonces tiene que ser async
        //metodo para guardar o actualizar
        async saveOrUpdate(){

          let me = this;
  me.bancoErrors.nombre = false;
  me.banco.nombre = me.banco.nombre.trim().toLowerCase(); // Convertir a minúsculas y eliminar espacios

  if (!me.banco.nombre) {
    me.bancoErrors.nombre = true;
    return; // Detener el proceso si el nombre está vacío o solo contiene espacios
  }

  // Verificar si el nombre del sabor ya existe en la lista
  const existingBanco = me.bancos.find(item => item.nombre.toLowerCase() === me.banco.nombre);
  if (existingBanco) {
    me.bancoErrors.nombre = true;
    // Mostrar mensaje de error
    this.$swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'El banco ya existe en la lista.',
      timer: 3000,
      timerProgressBar: true,
    });
    return; // Detener el proceso si el nombre ya existe
  }

          //let me = this;         //el de abajo es operrador ternario
          me.banco.nombre == ''  ? me.bancoErrors.nombre = true :  me.bancoErrors.nombre = false
          if(me.banco.nombre){     //operador ternario
                        // variable accion sera de agregar (add) y si no que actualice (upd)  
            let accion = me.banco.id == null ? "add" : "upd";
            if(accion == "add"){
              //guardar una marca (post en caso de agregar )
              await this.axios.post('/bancos', me.banco)
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
              await this.axios.put(`/bancos/${me.banco.id}`, me.banco)
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
          async eliminar(banco){
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
                me.editedBanco = me.bancos.indexOf(banco);
                this.axios.delete(`/bancos/${banco.id}`)
                .then(response =>{
                  me.verificarAccion(null,response.status,"del");
                }).catch(errors =>{
                  console.log(errors)
                })
              }
            })
          },
          //metodo para que muestre un msj y actualize la tabla cuando inserte una marca nueva  
          verificarAccion(banco, statusCode, accion){
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
                me.bancos.unshift(banco);
                Toast.fire({
                  icon: 'success',
                  title: 'banco Registrada con Exito'
                });
                break;
                case "upd":
                  Object.assign(me.bancos[me.editedBanco], banco);
                  Toast.fire({
                    icon: 'success',
                    'title': 'banco Actualizado con Exito'
                  });
                  break;
                  case "del":
                  if(statusCode == 200)
                 {
              try{
                me.bancos.splice(me.editedBanco,1);
                //se lanza el mensaje final
                Toast.fire({
                  icon: 'success',
                 'title': 'Banco Eliminado...!!!'
                });
              }catch(error)
              {
                console.log(error);
              }
            }else{
              Toast.fire({
                icon: 'warning',
               'title': 'error al eliminar el banco, intente de nuevo'
              });
            }
                    break;
              }
          },
        },
          mounted(){
            this.fetchBancos();
          },
      }
      
  </script>
  