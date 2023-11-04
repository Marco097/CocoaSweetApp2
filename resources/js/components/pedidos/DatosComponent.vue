<template>
    <div>
      <FormComponent @productSelected="addToCart" />
      <CartComponent :cart="cart" />
    </div>
  </template>
  
  <script>
  import FormComponent from './FormComponent.vue';
  import CartComponent from './carrito.vue';
  
  export default {
    components: {
      FormComponent,
      CartComponent
    },
    data() {
      return {
        cart: [], // Inicializa el carrito como un array vacío
      };
    },
    methods: {
      addToCart(product) {
        this.cart.push(product); // Agrega el producto al carrito
      }
    }
  }
  </script>