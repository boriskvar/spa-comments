<template>
    <div>
        <h2>{{ message }}</h2>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const message = ref('Hello, Vue 3!');
</script>
