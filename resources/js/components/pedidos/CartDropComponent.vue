<template>
    <div>
        <ul class="list-group">
            <li v-for="item in cart" class="list-group-item">
                <div class="row">
                    <div class="col-lg-3">
                        <img :src="`/images/productos/${item.imagen}`" style="width: 50px; height: 50px;">
                    </div>
                    <div class="col-lg-6">
                        <b>{{ item.nombre }}</b>
                        <br><small>Qty: {{ item.cantidad }}</small>
                    </div>
                    <div class="col-lg-3">
                        <p>${{ item.getPriceSum() }}</p>
                    </div>
                </div>
                <hr>
            </li>
            <li v-if="cart.length > 0" class="list-group-item">
                <div class="row">
                    <div class="col-lg-10">
                        <b>Total: </b>${{ getTotal() }}
                    </div>
                    <div class="col-lg-2">
                        <form action="{{ route('cart.remove') }}" method="POST">
                            {{ csrf_field() }}
                            <button class="btn btn-secondary btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </li>
            <li v-else class="list-group-item">
                Tu carrito esta vacío
            </li>
        </ul>
        <div class="row" style="margin: 0px;">
            <a class="btn btn-dark btn-sm btn-block" href="{{ route('cart.index') }}">
                CARRITO <i class="fa fa-arrow-right"></i>
            </a>
            <a class="btn btn-dark btn-sm btn-block" href="">
                CHECKOUT <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CartDrop',
    props: {
        cart: {
            type: Array,
            required: true,
        },
    },
    methods: {
        getPriceSum() {
            return this.cart.reduce((total, item) => total + item.getPriceSum(), 0);
        },
        getTotal() {
            return this.getPriceSum();
        },
    },
};
</script>
