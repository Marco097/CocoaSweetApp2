<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Listado de rellenos</h5>
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
                            <th scope="col" class="col-6">Relleno</th>
                            <th scope="col">Acciones</th>
                          </tr>
                        </thead>
                            <tbody>
                              <tr v-for="item in rellenos" :key = "item.id" >
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
  <div class="modal fade" id="rellenoModal" tabindex="-1" aria-labelledby="rellenoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="rellenoModalLabel">{{ formTitle }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!--definiendo el cuerpo del modal-->
          <div class="row">
            <div class="form-group col-12">
              <label for="nombre">Nombre del relleno</label>
              <input type="text" class="form-control" v-model="relleno.nombre">
              <span class="text-danger" v-show="rellenoErrors.nombre">Nombre del relleno es requerido</span>
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
                  rellenos:[],
                  relleno:{
                    id:null,
                    nombre: ""
                  },
                  editedRelleno: -1,
                  rellenoErrors:{
                    nombre:false
                  },
                  filters:[],
                  search: ''
              }
          },
          created: function(){
            this.filters = this.rellenos;
          },
          computed:{
        formTitle(){
            return this.relleno.id == null ? "Agregar relleno" : "Actualizar relleno";
          },
          btnTitle(){
          return this.relleno.id == null ? "Guardar" : "Actualizar";
          },
          items()
      {
        return this.marcas.filter(item =>{
          return item.nombre.toLowerCase().includes(this.seach.toLocaleLowerCase());
        } )
      }
        },
          methods:{
               //para que no haga una peticion directa 
              async fetchRellenos(){
                  let me = this;
                  await this.axios.get('/rellenos')
                  .then(response =>{
                     me.rellenos = response.data;
                  })
              },
              // para agregar nueva relleno
              showDialog(){
                this.relleno = {
                  id: null,
                  nombre: ""
                },
                this.rellenoErrors = {
                  nombre:false
                }
            $('#rellenoModal').modal('show');
          },
          //metodo para cerrar ventana de button (nuevo)
          hideDialog(){
            let me = this;
            setTimeout(() =>{
              me.relleno ={
                id: null,
                nombre: ""
              };
            }, 300)
            $('#rellenoModal').modal('hide');
  
          },
          //metodo para editar un sabor
          showDialogEditar(relleno){
            let me = this;
            $('#rellenoModal').modal('show');
            me.editedRelleno = me.rellenos.indexOf(relleno);
            me.relleno = Object.assign({}, relleno);
          },
          
          //este metodo es una meticion entonces tiene que ser async
        //metodo para guardar o actualizar
        async saveOrUpdate(){
          let me = this;         //el de abajo es operrador ternario
                me.rellenoErrors.nombre = false;
        me.relleno.nombre = me.relleno.nombre.trim().toLowerCase(); // Convertir a minúsculas y eliminar espacios

        if (!me.relleno.nombre) {
          me.rellenoErrors.nombre = true;
          return; // Detener el proceso si el nombre está vacío o solo contiene espacios
        }

        // Verificar si el nombre del sabor ya existe en la lista
        const existingRelleno = me.rellenos.find(item => item.nombre.toLowerCase() === me.relleno.nombre);
        if (existingRelleno) {
          me.rellenoErrors.nombre = true;
          // Mostrar mensaje de error
          this.$swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El Relleno ya existe en la lista.',
            timer: 3000,
            timerProgressBar: true,
          });
          return; // Detener el proceso si el nombre ya existe
        }


          me.relleno.nombre == ''  ? me.rellenoErrors.nombre = true :  me.rellenoErrors.nombre = false
          if(me.relleno.nombre){     //operador ternario
                        // variable accion sera de agregar (add) y si no que actualice (upd)  
            let accion = me.relleno.id == null ? "add" : "upd";
            if(accion == "add"){
              //guardar una relleno (post en caso de agregar )
              await this.axios.post('/rellenos', me.relleno)
              .then(response =>{
                console.log(response.data);
                if(response.status == 201)
                  {
                    me.verificarAccion(response.data.data,response.status,accion);
                    me.hideDialog();
                  }
              }).catch(errors =>{
                console.log(errors);
              })
             }else{
              //para actualizar una marca, con comias invertidas para poder concatenar algo que es texto con un valor
              await this.axios.put(`/rellenos/${me.relleno.id}`, me.relleno)
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
          async eliminar(relleno){
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
                me.editedRelleno = me.rellenos.indexOf(relleno);
                this.axios.delete(`/rellenos/${relleno.id}`)
                .then(response =>{
                  me.verificarAccion(null,response.status,"del");
                }).catch(errors =>{
                  console.log(errors);
                })
              }
            })
          },
          //metodo para que muestre un msj y actualize la tabla cuando inserte una marca nueva  
          verificarAccion(relleno, statusCode, accion){
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
                me.rellenos.unshift(this.relleno);
                Toast.fire({
                  icon: 'success',
                  title: 'relleno se ha agregado'
                });
                break;
                case "upd":
                  Object.assign(me.rellenos[me.editedRelleno], relleno);
                  Toast.fire({
                    icon: 'success',
                    'title': 'se actualizo el relleno'
                  });
                  break;
                  case "del":
                  if(statusCode == 200)
            {
              try{
                me.rellenos.splice(me.editedRelleno,1);
                //se lanza el mensaje final
                Toast.fire({
                  icon: 'success',
                 'title': 'Relleno Eliminado...!!!'
                });
              }catch(error)
              {
                console.log(error);
              }
            }else{
              Toast.fire({
                icon: 'warning',
               'title': 'error al eliminar el Relleno, intente de nuevo'
              });
            }
                    break;
              }
          },
  
        },
          mounted(){
            this.fetchRellenos();
          }
      }
      
  </script>
  