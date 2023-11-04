<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Listado de catalogos</h5>
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
                            <th scope="col">catalogos</th>
                            <th scope="col">Acciones</th>
                          </tr>
                        </thead>
                            <tbody>
                              <tr v-for="item in catalogos" :key = "item.id" >
                                  <td>{{ item.nombre }}</td>
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
  <div class="modal fade" id="catalogoModal" tabindex="-1" aria-labelledby="catalgoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="catalogoModalLabel">{{ formTitle }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!--definiendo el cuerpo del modal-->
          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control" v-model="catalogo.nombre">
              <span class="text-danger" v-show="CatalogoErrors.nombre">Nombre del catalogo es requerido</span>
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
                  catalogos:[],
                  catalogo:{
                    id:null,
                    nombre: ""
                  },
                  editedCatalogo: -1,
                  CatalogoErrors:{
                    nombre:false
                  },
                  filters:[],
                  search: ''
              }
          },
          created: function(){
            this.filters = this.catalogos;
          },
          computed:{
        formTitle(){
            return this.catalogo.id == null ? "Agregar catalogo" : "Actualizar catalogo";
          },
          btnTitle(){
          return this.catalogo.id == null ? "Guardar" : "Actualizar";
          },
          items()
      {
        return this.catalogo.filter(item =>{
          return item.nombre.toLowerCase().includes(this.seach.toLocaleLowerCase());
        } )
      }
        },
          methods:{
               //para que no haga una peticion directa 
              async fetchCatalogos(){
                  let me = this;
                  await this.axios.get('/catalogos')
                  .then(response =>{
                     me.catalogos = response.data;
                  })
              },
              // para agregar nueva marca
              showDialog(){
                this.catalogo = {
                  id: null,
                  nombre: ""
                },
                this.CatalogoErrors = {
                  nombre:false
                }
            $('#catalogoModal').modal('show');
          },
          hideDialog()
      {
        let me = this;
        setTimeout(() =>{
          me.marca = {
            id:null,
            nombre:""
          };
        },300)
        $('#catalogoModal').modal('hide');
      },
          //metodo para editar un sabor
          showDialogEditar(catalogo){
            let me = this;
            $('#catalogoModal').modal('show');
            me.editedCatalogo = me.catalogos.indexOf(catalogo);
            me.catalogo = Object.assign({}, catalogo);
          },
          //este metodo es una meticion entonces tiene que ser async
        //metodo para guardar o actualizar
        async saveOrUpdate(){
          let me = this;         //el de abajo es operrador ternario
          me.CatalogoErrors.nombre = false;
  me.catalogo.nombre = me.catalogo.nombre.trim().toLowerCase(); // Convertir a minúsculas y eliminar espacios

  if (!me.catalogo.nombre) {
    me.CatalogoErrors.nombre = true;
    return; // Detener el proceso si el nombre está vacío o solo contiene espacios
  }

  // Verificar si el nombre del sabor ya existe en la lista
  const existingCatalogo = me.catalogos.find(item => item.nombre.toLowerCase() === me.catalogo.nombre);
  if (existingCatalogo) {
    me.CatalogoErrors.nombre = true;
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

          me.catalogo.nombre == ''  ? me.CatalogoErrors.nombre = true :  me.CatalogoErrors.nombre = false
          if(me.catalogo.nombre){     //operador ternario
                        // variable accion sera de agregar (add) y si no que actualice (upd)  
            let accion = me.catalogo.id == null ? "add" : "upd";
            if(accion == "add"){
              //guardar una marca (post en caso de agregar )
              await this.axios.post('/catalogos', me.catalogo)
              .then(response =>{
                console.log(response.data);
                if(response.status == 201){
                  me.verificarAccion(response.data.data,response.status,accion);
                me.hideDialog();
                }
              }).catch(errors =>{
                console.log(errors);
              })
             }else{
              //para actualizar una marca, con comias invertidas para poder concatenar algo que es texto con un valor
              await this.axios.put(`/catalogos/${me.catalogo.id}`, me.catalogo)
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
          async eliminar(catalogo){
            let me = this;
            this.$swal.fire({
              title: 'seguro/a de eliminar este registro?',
              text: "No podras revertir la accion",
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si',
              cancelButtonText: 'No',
            }).then((result) =>{
              if(result.value){
                me.editedCatalogo = me.catalogos.indexOf(catalogo);
                this.axios.delete(`/catalogos/${catalogo.id}`)
                .then(response =>{
                  me.verificarAccion(null,response.status,"del");
                }).catch(errors =>{
                  console.log(errors)
                })
              }
            })
          },
          //metodo para que muestre un msj y actualize la tabla cuando inserte una marca nueva  
          verificarAccion(catalogo, statusCode, accion){
            let me = this;
            const Toast = this.$swal.mixin({
              toast:true,
              position: 'top-end',
              showConfirmButton:false,
              timer:2000,
              timerProgressBar: true
            });
            switch (accion){
              case "add":
                //se agrega al principio del arreglo marcas, la nueva marca
                me.catalogos.unshift(catalogo);
                Toast.fire({
                  icon: 'success',
                  title: 'Catalogo Registrada con Exito'
                });
                break;
                case "upd":
                  Object.assign(me.catalogos[me.editedCatalogo], catalogo);
                  Toast.fire({
                    icon: 'success',
                    'title': 'Catalogo Actualizado con Exito'
                  });
                  break;
                  case "del":
                  if(statusCode == 200)
            {
              try{
                me.catalogos.splice(me.editedCatalogo,1);
                //se lanza el mensaje final
                Toast.fire({
                  icon: 'success',
                 'title': 'Catalogo Eliminado...!!!'
                });
              }catch(error)
              {
                console.log(error);
              }
            }else{
              Toast.fire({
                icon: 'warning',
               'title': 'error al eliminar el Catalogo, intente de nuevo'
              });
            }
                    break;
              }
          },
  
        },
          mounted(){
            this.fetchCatalogos();
          }
      }
      
  </script>
  