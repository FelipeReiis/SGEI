<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div class="dashboard-header">
          <h2>Dashboard</h2>
          <p>Bem-vindo de volta! Aqui está o resumo.</p>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-info">
                    <span class="card-label">Usuários Ativos</span>
                    <strong class="card-value">2,543</strong>
                    <span class="card-grow positive">+12% vs mês anterior</span>
                </div>
                <div class="card-icon">👥</div>
            </div>

            <div class="card">
                <div class="card-info">
                    <span class="card-label">Receita</span>
                    <strong class="card-value">R$ 45.2k</strong>
                    <span class="card-grow positive">+23% vs mês anterior</span>
                </div>
                <div class="card-icon pink-bg">💲</div>
            </div>

             <div class="card">
                <div class="card-info">
                    <span class="card-label">Crescimento</span>
                    <strong class="card-value">18.5%</strong>
                    <span class="card-grow negative">+5% vs mês anterior</span>
                </div>
                <div class="card-icon">📈</div>
            </div>
        </div>

        <div class="content-section">
            <h3>Atividades Recentes</h3>
            <div class="placeholder-list">
                <div class="list-item" v-for="i in 3" :key="i">
                    <div class="dot"></div>
                    <div>
                        <strong>Novo usuário registrado</strong>
                        <p>Há 2 horas</p>
                    </div>
                </div>
            </div>
        </div>

    </MainLayout>
</template>

<style scoped>
.dashboard-header { margin-bottom: 30px; }
.dashboard-header h2 { font-size: 2rem; color: #333; margin: 0; }
.dashboard-header p { color: #888; }

/* Grid dos Cards */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border: 1px solid #f0f0f0;
}

.card-info { display: flex; flex-direction: column; }
.card-label { color: #888; font-size: 0.9rem; margin-bottom: 5px; }
.card-value { font-size: 1.8rem; color: #222; margin-bottom: 5px; }
.card-grow { font-size: 0.8rem; font-weight: 500; }
.card-grow.positive { color: #10b981; } /* Verde */

.card-icon {
    background-color: #ff0055;
    color: white;
    width: 50px; height: 50px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
}

/* Área inferior */
.content-section {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
}

.list-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}
.dot { width: 10px; height: 10px; background: #ff0055; border-radius: 50%; margin-right: 15px; }
</style>
