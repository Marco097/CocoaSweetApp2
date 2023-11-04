<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <h5 class="float-start">Listado de Productos</h5>
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
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Sabor</th>
                                    <th scope="col">Relleno</th>
                                    <th scope="col">Catalogo</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Existencias</th>
                                    <th scope="col">Fecha en Venta</th>
                                    <th scope="col">Fecha de Vencimiento</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                          <tbody>
                                <tr v-for="item in productos" :key="item.id">
                                    <td>{{ item.nombre }}</td>
                                    <td>{{ item.descripcion }}</td>
                                    <td>
                                        <span v-for="sabor in item.sabores">{{ sabor.nombre }}</span>
                                    </td>
                                    <td>{{ item.relleno && item.relleno.nombre ? item.relleno.nombre : '-' }}</td>
                                    <td>{{ item.catalogo && item.catalogo.nombre ? item.catalogo.nombre : '-' }}</td>
                                    <td>{{ item.precio }}</td>
                                    <td>{{ item.existencias }}</td>
                                    <td>{{ item.hecho }}</td>
                                    <td>{{ item.vencimiento }}</td>
                                    <td><img :src="`/images/productos/${item.imagen || ''}`" :alt="`${item.imagen || ''}`"
                                            style="width:100px;height: 100px"></td>

                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            @click="showDialogEditar(item)">Editar</button>
                                        &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="eliminar(item)">Eliminar</button>
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
    <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-7" id="productoModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Definiendo el cuerpo del formulario modal -->
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" v-model="producto.nombre">
                            <span class="text-danger" v-show="productoErrors.nombre">nombre requerido</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" class="form-control" v-model="producto.descripcion">

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="sabor">Sabor</label>
                            <select v-model="producto.sabor_id" class="form-control">
                                <option v-for="sab in sabores" :key="sab.id">{{ sab.nombre }}</option>
                            </select>
                            <span class="text-danger" v-show="productoErrors.sabor">Seleccione un sabor</span>
                        </div>



                        <div class="form-group col-6">
                            <label for="relleno">Relleno</label>
                            <select v-model="producto.relleno_id" class="form-control">
                                <option v-for="relle in rellenos" :value="relle.id">
                                    {{ relle.nombre }}
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-6">
                            <label for="precio">Precio</label>
                            <input type="text" class="form-control" v-model="producto.precio">
                            <span class="text-danger" v-show="productoErrors.precio">precio es requerido</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="existencias">Existencias</label>
                            <input type="number" class="form-control" v-model="producto.existencias">
                            <span class="text-danger" v-show="productoErrors.existencias">Existencias es requerido</span>
                        </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                            <label for="catalogo">Catalogo</label>
                            <select v-model="producto.catalogo_id" class="form-control">
                                <option v-for="catalogo in catalogos" :value="catalogo.id" >
                                {{ catalogo.nombre }}
                                </option>
                            </select>
                            <span class="text-danger" v-show="productoErrors.sabor">Seleccione un Catalogo</span>
                        </div>
                        <div class="row">
                        <div class="form-group col-6">
                            <label for="hecho">Fecha a la venta</label>
                            <input type="date" class="form-control" v-model="producto.hecho">
                            <span class="text-danger" v-show="productoErrors.hecho">la fecha es requerida</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="hecho">Fecha vencimiento</label>
                            <input type="date" class="form-control" v-model="producto.vencimiento">
                            <span class="text-danger" v-show="productoErrors.hecho">la fecha es requerida</span>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                          <label for="formfile" class="form-label">Seleccione una imagen para el producto</label>
                          <input type="file" class="form-control" accept="image/*" @change="getImage">
                      </div>
                      <div class="col6">
                          <figure>
                              <img v-if="imagePreview" :src="imagePreview" width="200" height="150"/>
                          </figure>
                      </div>
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
            productos: [],
            producto: {
                id: null,
                nombre: "",
                descripcion:null,
                sabor_id: null,
                relleno_id:null,
                precio: 0,
                existencias:"",
                catalogo_id:null,
                imagen: null,
                sabor: {
                    nombre: null,
                },
                relleno: null,
                catalogo:null

            },
           imageCar:null,
            imagePreview:null,
            editedProducto: -1,
            productoErrors: {
                nombre: false,
                sabor: false,
                //relleno: false,
                precio: false,
                catalogo: false,
            },
            filters:[],
            search:'',
            sabores:[],
            rellenos:[],
            catalogos:[],
        }
    },
    created: function(){
          this.filters = this.productos;
        },
    computed:{
        formTitle(){
            return this.producto.id == null ? "Agregar producto" : "Actualizar producto";
          },
          btnTitle(){
          return this.producto.id == null ? "Guardar" : "Actualizar";
          }
        },
    methods: {
        async fetchProductos() {
            let me = this;
            await this.axios.get('/productos')
                .then(response => {
                    me.productos = response.data;
                    console.log('Productos:', me.productos);
                })
               // me.fetchSabores();
        },
        async fetchSabores(){
                  let me = this;
                  await this.axios.get('/sabores')
                  .then(response =>{
                     me.sabores = response.data;
                     console.log('Sabores:', me.sabores);
                  })
              },

        async fetchRellenos() {
            let me = this;
            await this.axios.get('/rellenos')
                .then(response => {
                    me.rellenos = response.data;
                })
        },
        async fetchCatalogos(){
                  let me = this;
                  await this.axios.get('/catalogos')
                  .then(response =>{
                     me.catalogos = response.data;
                  })
              },
        showDialog() {
            this.producto = {
                id: null,
                nombre: "",
                descripcion: "",
                precio: 0,
                existencias: 0,
                imagen: "",
                sabor: null,
                relleno: null,
                catalogo:null,
                sabor_id:null,
                relleno_id:null,
                catalogo_id:null,
            };
            imageCar:null,
            this.imagePreview = null;
            this.productoErrors = {
                nombre: false,
                descripcion: false,
                precio: false,
                existencias: false,
                imagen: false,
                sabor: false,
               // relleno: false,
                catalogo: false,
            };
            $('#productoModal').modal('show');
        },
        showDialogEditar(producto) {
    let me = this;
    $('#productoModal').modal('show');

    // Verifica si 'producto' es nulo o no está definido antes de intentar acceder a sus propiedades.
    if (producto) {
        me.editedProducto = me.productos.indexOf(producto);
        me.producto = Object.assign({}, producto);
        me.imagePreview = "/images/productos/" + (me.producto.imagen || ''); // Asegúrate de que 'imagen' no sea nulo.

        // Verifica si 'producto.sabor' y 'producto.relleno' son nulos o no están definidos antes de intentar acceder a sus propiedades.
        me.producto.sabor_id = producto.sabor ? producto.sabor.id : null;
        me.producto.relleno_id = producto.relleno ? producto.relleno.id : null;
    }
},


        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.producto = {
                    id: null,
                nombre: "",
                descripcion: "",
                precio: 0,
                existencias: 0,
                imagen: "",
                sabor: null,
                relleno: null,
                catalogo:null,
                },
                me.imagePreview = null,
                me.imageCar = null
            }, 300);
            $('#productoModal').modal('hide');
        },

        async saveOrUpdate() {
            let me = this;
            me.producto.nombre == '' ? me.productoErrors.nombre = true : me.productoErrors.nombre = false
            me.producto.sabor_id == null ? me.productoErrors.sabor = true : me.productoErrors.sabor_id = false
            me.producto.relleno_id == null ? me.productoErrors.relleno = true : me.productoErrors.relleno_id = false
            me.producto.descripcion == '' ? me.productoErrors.descripcion = true : me.productoErrors.descripcion = false
            me.producto.catalogo_id == null ? me.productoErrors.catalogo = true : me.productoErrors.catalogo_id = false
            me.producto.precio == null ? me.productoErrors.precio = true : me.productoErrors.precio = false
            me.producto.existencias == '' ? me.productoErrors.existencias= true : me.productoErrors.existencias = false

            if (me.producto.nombre) {

                let accion = me.producto.id == null ? "add" : "upd";

                 me.producto.sabor = {
                    "id" : me.producto.sabor_id
                };
                me.producto.relleno = {
                    "id" : me.producto.relleno_id
                };
                me.producto.catalogo = {
                    "id" : me.producto.catalogo_id
                };
                let formData = new FormData();
                formData.append("nombre", me.producto.nombre);
                formData.append("descripcion", me.producto.descripcion);
                formData.append("precio", me.producto.precio);
                formData.append("existencias", me.producto.existencias);
                formData.append("sabor_id", me.producto.sabor_id);

                formData.append("catalogo_id", me.producto.catalogo_id);

                 if (me.producto.relleno_id !== null) {
                    formData.append("relleno_id", me.producto.relleno_id);
                }

                if(me.imageCar != null)
                {
                  formData.append("imagen", me.imageCar);
                }
                //definiendo encabezado de peticion
                let headers = {
                  headers: {
                      "Content-type": "multipart/form-data"
                  }
                };

                if (accion == "add") {
                    //peticion para guardar una auto
                    //me.producto.imagen = "none.jpg";
                    formData.append("estado",me.producto.estado);
                    await this.axios.post('/productos', me.producto) //cambie esto alex
                        .then(response => {
                            console.log(response.data);
                            if (response.status == 201) {
                                me.verificarAccion(response.data.data,response.status,accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                } else {
                    //peticion para actualizar una marca

                   //formData.append("estado",me.producto.estado)

                   //formData.append("id",me.producto.id)
                   //peticion par actualizar un auto
                   await this.axios.put(`/productos/${me.producto.id}`, me.producto)
                    .then(response => {
                            //console.log(response.data);
                            if(response.status == 202){
                             me.verificarAccion(response.data.data,response.status,accion);
                             me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })

                }
            }
           // console.log(this.saveOrUpdate)
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
        verificarAccion(producto, statusCode, accion) {
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
                    me.fetchProductos();
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
                        me.productos.splice(this.editedProducto, 1);
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

  getImage(event){
      let file = event.target.files[0];
      this.imageCar = file;
      this.loadImage(file);
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
        this.fetchProductos();
        this.fetchSabores();
        this.fetchRellenos();
        this.fetchCatalogos();
       // this.fetchCoberturas()
    }
  }
  </script>
