<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
// Capturamos as mensagens compartilhadas pelo Middleware
const flash = computed(() => page.props.flash);
const visivel = ref(false);

// Sempre que a mensagem mudar, mostramos o alerta e escondemos após 3 segundos
watch(() => flash.value, (novo) => {
    if (novo.sucesso || novo.erro) {
        visivel.value = true;
        setTimeout(() => visivel.value = false, 4000);
    }
}, { deep: true });
</script>

<template>
    <div v-if="visivel" class="flash-container">
        <div v-if="flash.sucesso" class="alert alert-sucesso">
            <span>✅ {{ flash.sucesso }}</span>
            <button @click="visivel = false">×</button>
        </div>

        <div v-if="flash.erro" class="alert alert-erro">
            <span>⚠️ {{ flash.erro }}</span>
            <button @click="visivel = false">×</button>
        </div>
    </div>
</template>

<style scoped>
.flash-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}
.alert {
    padding: 15px 25px;
    border-radius: 12px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    animation: slideIn 0.5s ease;
}
.alert-sucesso { background-color: #28a745; } /* Verde para sucesso */
.alert-erro { background-color: #ff0055; }    /* Rosa Neon para erro */

button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>
