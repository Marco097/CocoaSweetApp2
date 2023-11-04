<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="row">
        <div class="col-sm-4" v-for="(item, index) in productos" :key="index">
          <div class="card">
            <img :src="`/images/productos/${item.imagen}`" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title text-bold">{{ item.nombre }}</h5>
              <p class="card-text">Precio <b class="text-warning">${{ item.precio }}</b></p>             
              <div class="input-group mb-3">
                <input type="number" v-model="item.cantidad" min="1" class="form-control col-3" placeholder="Cantidad">
                <a href="#" class="btn btn-primary" @click="addToReserva(item, item.cantidad)"> <i class="fas fa-shopping-cart" style="font-size: 20px; color: #000000;"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      showModal: false,
      productos: [],
      reservaForm: {
        id: null,
        user: null,
        detallePedido: [],     
        cantidad: null,
      },
      productoSeleccionado: null,
      imagePreview: null,
      mostrarProducto: -1,
      search: "",
      sabores: [],
      rellenos: [],
      coberturas: [],
      showCarrito: false, 
      
    };
  },
  methods: {
    async fetchProductos() {
      let me = this;
      await this.axios.get("/productos-reservas").then((response) => {
        console.log(response.data);
        me.productos = response.data.map((item) => ({
          item,
          cantidad: 0,
        }));
      });
    },

    actualizarCarrito(nuevoCarrito) {
      this.carrito = nuevoCarrito;
    },
    
    addToReserva(item, index) {
    let me = this;
    me.reservaForm.detallePedido.push({
        id: null,
        cantidad:me.cantidad,
        producto: item,
    });
    console.log(me.reservaForm.detallePedido);

      },

  },
  mounted() {
    this.fetchProductos();
   
    console.log(this.user);
  },
};
</script>