<script setup>
import {computed} from "vue";

const props = defineProps({
    product: {
        type: {},
        required: true
    }
})

const priceShow = computed(() => {
    return props.product.price ? props.product.price.toLocaleString('it-IT', {style: 'currency', currency: 'VND'}) : "";
})
const originalPriceShow = computed(() => {
    return props.product.originalPrice ? props.product.originalPrice.toLocaleString('it-IT', {
        style: 'currency',
        currency: 'VND'
    }) : "";
})
</script>

<template>
    <div class="card h-100">
        <img :src="product.image" class="card-img-top" alt="...">
        <div class="card-body pb-2">
            <h6 class="card-title">{{ product.name }}</h6>
            <span class="text-danger">{{ priceShow }}</span>
            <span class="p-2 text-muted font-sm text-decoration-line-through">{{ originalPriceShow }}</span>
            <span class="p-2 text-muted font-sm badge bg-warning "
                  v-if="product.discount">{{ product.discount }}</span>
        </div>
        <div class="card-footer text-muted">
            <span class="font-sm float-start" v-if="product.itemSoldCntShow">{{ product.itemSoldCntShow }}</span>
            <span class="font-sm float-end">{{ product.location }}</span>
        </div>
    </div>
</template>

<style scoped>
.font-sm {
    font-size: small;
}

.card:hover {
    border: 1px solid gray;
    cursor: pointer;
}
</style>
