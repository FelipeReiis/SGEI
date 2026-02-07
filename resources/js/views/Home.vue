<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>

        <div class="dashboard-content">
            <h1>Bem-vindo ao Dashboard</h1>
            <p>Este conteúdo está sendo injetado no SLOT do grid.</p>

            <div class="cards">
                <div class="card">Vendas: 100</div>
                <div class="card">Usuários: 50</div>
                <div class="card">Lucro: R$ 500</div>
            </div>
        </div>

    </MainLayout>
</template>

<style scoped>
/* Estilo específico apenas desta página */
.cards { display: flex; gap: 20px; margin-top: 20px; }
.card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
</style>
