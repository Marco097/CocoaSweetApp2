<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Listado de Toppings</h5>
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
                            <th scope="col">Precio</th>
                            <th scope="col">Acciones</th>
                          </tr>
                        </thead>
                            <tbody>
                              <tr v-for="item in coberturas" :key = "item.id">
                                  <td>{{ item.nombre }}</td>
                                  <td>{{ item.precio }}</td>
                                <td>
                                  <button type="button" class="btn btn-primary btn-sm" @click="showDialogEditar(item)">Editar</button>
                                  &nbsp;
                                  <button type="button" class="btn btn-danger btn-sm" @click="eliminar(item)">Eliminar</button>
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
  <div class="modal fade" id="coberturaModal" tabindex="-1" aria-labelledby="coberturaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="coberturaModalLabel">{{ formTitle }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!--definiendo el cuerpo del modal-->
          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control" v-model="cobertura.nombre">
              <span class="text-danger" v-show="coberturaErrors.nombre">Nombre del topping es requerido</span>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">precio</label>
              <input type="text" class="form-control" v-model="cobertura.precio">
              <span class="text-danger" v-show="coberturaErrors.precio">Precio del topping es requerido</span>
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
                  coberturas:[],
                  cobertura:{
                    id:null,
                    nombre: "",
                    precio: 0
                  },
                  editedCobertura: -1,
                  coberturaErrors:{
                    nombre:false,
                    precio:false
                  },
                  filters:[],
                  search: '',
                  existingCoberturas: {} // Objeto para mantener un registro de coberturas existentes por nombre
              }
          },
          created: function(){
            this.filters = this.coberturas;
          },
          computed:{
        formTitle(){
            return this.cobertura.id == null ? "Agregar Topping" : "Actualizar Topping";
          },
          btnTitle(){
          return this.cobertura.id == null ? "Guardar" : "Actualizar";
          },
          items()
      {
        return this.coberturas.filter(item =>{
          return item.nombre.toLowerCase().includes(this.seach.toLocaleLowerCase());
        } )
      }
        },
          methods:{
               //para que no haga una peticion directa 
              async fetchCoberturas(){
                  let me = this;
                  await this.axios.get('/coberturas')
                  .then(response =>{
                     me.coberturas = response.data;
                    // Actualizar las coberturas existentes en el objeto existingCoberturas
                     me.existingCoberturas = {};
                        for (const cobertura of me.coberturas) {
                            me.existingCoberturas[cobertura.nombre] = true;
                        }

                  })
              },
              // para agregar nueva marca
              showDialog(){
                this.cobertura = {
                  id: null,
                  nombre: "",
                  precio: 0
                },
                this.coberturaErrors = {
                  nombre:false,
                  precio:false
                }
            $('#coberturaModal').modal('show');
          },
          hideDialog()
      {
        let me = this;
        setTimeout(() =>{
          me.cobertura = {
            id:null,
            nombre:"",
            precio:0
          };
        },300)
        $('#coberturaModal').modal('hide');
      },
          //metodo para editar un sabor
          showDialogEditar(cobertura){
            let me = this;
            $('#coberturaModal').modal('show');
            me.editedCobertura = me.coberturas.indexOf(cobertura);
            me.cobertura = Object.assign({}, cobertura);
          },
          
    
          //este metodo es una meticion entonces tiene que ser async
        //metodo para guardar o actualizar
        async saveOrUpdate(){
          let me = this;         //el de abajo es operrador ternario

          me.coberturaErrors.nombre = false;
          me.coberturaErrors.precio = false;

                // Validar que el nombre no esté vacío
                if (me.cobertura.nombre === "") {
                    me.coberturaErrors.nombre = true;
                }

                // Validar que el precio no sea null o no sea un número válido
                if (me.cobertura.precio === null || isNaN(me.cobertura.precio)) {
                    me.coberturaErrors.precio = true;
                }

                // Validar si el nombre de la cobertura ya existe
                if (me.existingCoberturas[me.cobertura.nombre] && !me.cobertura.id) {
                    me.coberturaErrors.nombre = true;
                    return; // Detener el proceso si hay error
                }

                if (!me.coberturaErrors.nombre && !me.coberturaErrors.precio) {
                    // Resto del código para guardar o actualizar la cobertura...
                }



          me.cobertura.nombre == ''  ? me.coberturaErrors.nombre = true :  me.coberturaErrors.nombre = false
          me.cobertura.precio == null  ? me.coberturaErrors.precio = true :  me.coberturaErrors.precio = false
          if(me.cobertura.nombre){     //operador ternario
                        // variable accion sera de agregar (add) y si no que actualice (upd)  
            let accion = me.cobertura.id == null ? "add" : "upd";
            
            let formData = new FormData();
            formData.append("nombre", me.cobertura.nombre);
            formData.append("precio", me.cobertura.precio);

            if(accion == "add"){
              //guardar una marca (post en caso de agregar )
              await this.axios.post('/coberturas', formData )
              .then(response =>{
                console.log(response.data);
                me.verificarAccion(response.data.data,response.status,accion);
                me.hideDialog();
              }).catch(errors =>{
                console.log(errors);
              })
             }else{
              //para actualizar una marca, con comias invertidas para poder concatenar algo que es texto con un valor
              await this.axios.put(`/coberturas/${me.cobertura.id}`, me.cobertura)
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
          async eliminar(cobertura){
            let me = this;
            this.$swal.fire({
              title: 'seguro/a de eliminar este registro?',
              text: "No podras revertir la accion",
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'si',
              cancelButtonText: 'no',
            }).then((result) =>{
              if(result.value){
                me.editedCobertura = me.coberturas.indexOf(cobertura);
                this.axios.delete(`/coberturas/${cobertura.id}`)
                .then(response =>{
                  me.verificarAccion(null,response.status,"del");
                }).catch(errors =>{
                  console.log(errors);
                })
              }
            })
          },
          //metodo para que muestre un msj y actualize la tabla cuando inserte una marca nueva  
          verificarAccion(cobertura, statusCode, accion){
            let me = this;
            const Toast = this.$swal.mixin({
              toast:true,
              position: 'top-end',
              showConfirmButton:false,
              timer:2000,
              timerProgressBar: true,
            });
            switch (accion){
              case "add":
                //se agrega al principio del arreglo marcas, la nueva marca
                me.coberturas.unshift(cobertura);
                Toast.fire({
                  icon: 'success',
                  title: 'Topping Registrado con Exito'
                });
                break;
                case "upd":
                  Object.assign(me.coberturas[me.editedCobertura], cobertura);
                  Toast.fire({
                    icon: 'success',
                    'title': 'Topping Actualizado con Exito'
                  });
                  break;
                  case "del":
                  if(statusCode == 200)
            {
              try{
                me.coberturas.splice(me.editedCobertura,1);
                //se lanza el mensaje final
                Toast.fire({
                  icon: 'success',
                 'title': 'Topping Eliminado...!!!'
                });
              }catch(error)
              {
                console.log(error);
              }
            }else{
              Toast.fire({
                icon: 'warning',
               'title': 'error al eliminar el Topping, intente de nuevo'
              });
            }
                    break;
              }
          }
  
        },
          mounted(){
            this.fetchCoberturas();
          },
      }
      
  </script>
  