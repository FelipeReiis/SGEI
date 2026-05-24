<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
    import { Head, router, usePage } from '@inertiajs/vue3';
    const page = usePage();
    // Resgata o nome do usuário logado (geralmente mapeado em auth.user.name)
    const userName = page.props.auth?.user?.name || 'teste';

    // Função para navegar usando o Inertia para as sessões do menu
    const irPara = (rota) => {
        router.visit(rota);
    };
</script>

<template>
    <Head title="Bem-vindo" />

    <MainLayout>
        <div class="dashboard-header">
          <h2>Olá, {{ userName }}! 👋</h2>
          <p>Seja bem-vindo ao SGEI. Para onde você gostaria de ir hoje?</p>
        </div>

        <div class="cards-grid welcome-grid">

            <div class="card clickable-card" @click="irPara('/secretaria')">
                <div class="card-info">
                    <span class="card-label">Área Administrativa</span>
                    <strong class="card-value-title">Secretaria</strong>
                    <p class="card-description">Gerencie alunos, turmas e professores.</p>
                    <button class="btn-welcome">Acessar Secretaria</button>
                </div>
            </div>

            <div class="card clickable-card" @click="irPara('/inscricoes')">
                <div class="card-info">
                    <span class="card-label">Fluxo de Alunos</span>
                    <strong class="card-value-title">Inscrições</strong>
                    <p class="card-description">Acompanhe novos cadastros e controle o status de matrículas.</p>
                    <button class="btn-welcome">Ver Inscrições</button>
                </div>
            </div>

        </div>

        <div class="content-section help-section">
            <p>Precisa de ajuda? Entre em contato com o administrador do sistema.</p>
        </div>

    </MainLayout>
</template>

<style scoped>
/* --- SEUS ESTILOS ORIGINAIS PRESERVADOS --- */
.dashboard-header { margin-bottom: 30px; }
.dashboard-header h2 { font-size: 2rem; color: #333; margin: 0; }
.dashboard-header p { color: #888; }

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

.card-info { display: flex; flex-direction: column; width: 80%; }
.card-label { color: #888; font-size: 0.9rem; margin-bottom: 5px; }
.card-icon {
    background-color: #ff0055;
    color: white;
    width: 50px; height: 50px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.content-section {
    background: white;
    padding: 20px 30px;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    border: 1px solid #f0f0f0;
}

/* --- NOVOS ESTILOS ADAPTADOS AO SEU DESIGN --- */
.welcome-grid {
    /* Ajusta para duas colunas maiores ao invés de três menores */
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
}

.card-value-title {
    font-size: 1.6rem;
    color: #222;
    margin-bottom: 8px;
    font-weight: 700;
}

.card-description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 20px;
    min-height: 40px; /* Mantém os dois cards alinhados */
}

/* Botão no tom de rosa (#ff0055) da sua logo */
.btn-welcome {
    background-color: #ff0055;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    align-self: flex-start;
    transition: background 0.2s ease, transform 0.1s ease;
}

/* Efeitos de Interação (Hover) */
.clickable-card {
    cursor: pointer;
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.clickable-card:hover {
    transform: translateY(-3px);
    border-color: #ffb3cc;
    box-shadow: 0 8px 16px rgba(255, 0, 85, 0.04);
}

.clickable-card:hover .btn-welcome {
    background-color: #d60047;
}

.help-section p {
    color: #777;
    font-size: 0.85rem;
    margin: 0;
    text-align: center;
}
</style>
