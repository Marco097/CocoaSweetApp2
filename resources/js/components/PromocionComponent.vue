<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Listado de Promociones</h5>
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
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripcion</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Fecha Final</th>
                            <th scope="col">Precio de Promo</th>
                            <th scope="col">Acciones</th>
                          </tr>
                        </thead>
                            <tbody>
                              <tr v-for="item in promociones" :key = "item.id" >
                                  <td>{{ item.nombre }}</td>
                                  <td>{{ item.Descripcion }}</td>
                                  <td>{{ item.tipo=='2x1' ? '2x1' : (item.tipo=='50%' ? 'Mitad de precio': (item.tipo=='P' ? 'Pickup' : 'limosina')) }}</td>
                                  <td>{{ item.Descuento }}</td>
                                  <td>{{ item.fecha_inicio }}</td>
                                  <td>{{ item.fecha_final }}</td>
                                  <td>{{ item.precio_descuento }}</td>
                                <td>
                                  <button class="btn btn-primary btn-sm" @click="showDialogEditar(item)">Editar</button>
                                  &nbsp;
                                  <button class="btn btn-danger btn-sm" @click="eliminar(item)">Eliminar</button>
                                  &nbsp;
                                  <button class="btn btn-success btn-sm" @click="(item)">Activa</button>

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
  <div class="modal fade" id="saborModal" tabindex="-1" aria-labelledby="saborModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="saborModalLabel">{{ formTitle }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!--definiendo el cuerpo del modal-->
          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control" v-model="sabor.nombre">
              <span class="text-danger" v-show="promoErrors.nombre">Nombre del sabor es requerido</span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-12">
              <label for="descripcion">Descripcion</label>
              <input type="text" class="form-control" v-model="sabor.descripcion">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-6">
                <label for="tipo">Tipo</label>
                    <select v-model="promocion.tipo" class="form-control">
                        <option v-for="tipoPromocion in tipos" :value="tipoPromocion.value" >
                            {{ tipoPromocion.text }}
                        </option>
                    </select>
                <span class="text-danger" v-show="promoErrors.tipo">Seleccione tipo</span>
            </div>

            <div class="form-group col-12">
              <label for="descuento">Descuento</label>
              <input type="text" class="form-control" v-model="sabor.descuento">
            </div>
          </div>

          <div class="row">
                        <div class="form-group col-6">
                            <label for="hecho">Fecha de Inicio</label>
                            <input type="date" class="form-control" v-model="promocion.fecha_inicio">
                            <span class="text-danger" v-show="promoErrors.fecha_inicio">la fecha es requerida</span>
                        </div>

                        <div class="form-group col-6">
                            <label for="hecho">Fecha Final</label>
                            <input type="date" class="form-control" v-model="promocion.fecha_final">
                            <span class="text-danger" v-show="promoErrors.fecha_final">la fecha es requerida</span>
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
                  promociones:[],
                  promocion:{
                    id:null,
                    nombre: ""
                  },
                  editedSabor: -1,
                  saborErrors:{
                    nombre:false
                  },
                  filters:[],
                  search: ''
              }
          },
          created: function(){
            this.filters = this.sabores;
          },
          computed:{
        formTitle(){
            return this.sabor.id == null ? "Agregar sabor" : "Actualizar sabor";
          },
          btnTitle(){
          return this.sabor.id == null ? "Guardar" : "Actualizar";
          },
        items()
          {
            return this.sabores.filter(item =>{
              return item.nombre.toLowerCase().includes(this.seach.toLocaleLowerCase());
            } )
          }
        },
          methods:{
               //para que no haga una peticion directa
              async fetchSabores(){
                  let me = this;
                  await this.axios.get('/sabores')
                  .then(response =>{
                     me.sabores = response.data;
                  })
              },
              // para agregar nueva marca
              showDialog(){
                this.sabor = {
                  id: null,
                  nombre: ""
                },
                this.saborErrors = {
                  nombre:false
                }
            $('#saborModal').modal('show');
          },
          hideDialog()
      {
        let me = this;
        setTimeout(() =>{
          me.sabor = {
            id:null,
            nombre:""
          };
        },300)
        $('#saborModal').modal('hide');
      },
          //metodo para editar un sabor
          showDialogEditar(sabor){
            let me = this;
            $('#saborModal').modal('show');
            me.editedSabor = me.sabores.indexOf(sabor);
            me.sabor = Object.assign({}, sabor);
          },
          //este metodo es una meticion entonces tiene que ser async
        //metodo para guardar o actualizar
        async saveOrUpdate(){

          let me = this;
  me.saborErrors.nombre = false;
  me.sabor.nombre = me.sabor.nombre.trim().toLowerCase(); // Convertir a minúsculas y eliminar espacios

  if (!me.sabor.nombre) {
    me.saborErrors.nombre = true;
    return; // Detener el proceso si el nombre está vacío o solo contiene espacios
  }

  // Verificar si el nombre del sabor ya existe en la lista
  const existingSabor = me.sabores.find(item => item.nombre.toLowerCase() === me.sabor.nombre);
  if (existingSabor) {
    me.saborErrors.nombre = true;
    // Mostrar mensaje de error
    this.$swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'El sabor ya existe en la lista.',
      timer: 3000,
      timerProgressBar: true,
    });
    return; // Detener el proceso si el nombre ya existe
  }


          //let me = this;         //el de abajo es operrador ternario
          me.sabor.nombre == ''  ? me.saborErrors.nombre = true :  me.saborErrors.nombre = false
          if(me.sabor.nombre){     //operador ternario
                        // variable accion sera de agregar (add) y si no que actualice (upd)
            let accion = me.sabor.id == null ? "add" : "upd";
            if(accion == "add"){
              //guardar una marca (post en caso de agregar )
              await this.axios.post('/sabores', me.sabor)
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
              await this.axios.put(`/sabores/${me.sabor.id}`, me.sabor)
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
          async eliminar(sabor){
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
                me.editedSabor = me.sabores.indexOf(sabor);
                this.axios.delete(`/sabores/${sabor.id}`)
                .then(response =>{
                  me.verificarAccion(null,response.status,"del");
                }).catch(errors =>{
                  console.log(errors)
                })
              }
            })
          },
          //metodo para que muestre un msj y actualize la tabla cuando inserte una marca nueva
          verificarAccion(sabor, statusCode, accion){
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
                me.sabores.unshift(sabor);
                Toast.fire({
                  icon: 'success',
                  title: 'sabor Registrada con Exito'
                });
                break;
                case "upd":
                  Object.assign(me.sabores[me.editedSabor], sabor);
                  Toast.fire({
                    icon: 'success',
                    'title': 'sabor Actualizado con Exito'
                  });
                  break;
                  case "del":
                  if(statusCode == 200)
                 {
              try{
                me.sabores.splice(me.editedSabor,1);
                //se lanza el mensaje final
                Toast.fire({
                  icon: 'success',
                 'title': 'Sabor Eliminado...!!!'
                });
              }catch(error)
              {
                console.log(error);
              }
            }else{
              Toast.fire({
                icon: 'warning',
               'title': 'error al eliminar el sabor, intente de nuevo'
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
