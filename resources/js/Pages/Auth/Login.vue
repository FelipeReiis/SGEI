<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Acesso ao Sistema" />

    <div class="login-container">
        <div class="login-card shadow-lg">

            <div class="text-center mb-5">
                <h1 class="logo-text">SGEI</h1>
                <p class="text-muted small">Faça login para acessar sua conta</p>
            </div>

            <div v-if="status" class="alert alert-success mb-4 text-center">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="form-label" for="email">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control custom-input"
                        :class="{'is-invalid': form.errors.email}"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="seu@email.com"
                    >
                    <div v-if="form.errors.email" class="invalid-feedback mt-1 text-danger small">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password">Senha</label>
                    <input
                        id="password"
                        type="password"
                        class="form-control custom-input"
                        :class="{'is-invalid': form.errors.password}"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    <div v-if="form.errors.password" class="invalid-feedback mt-1 text-danger small">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label class="form-check-label d-flex align-items-center text-muted small cursor-pointer">
                        <input type="checkbox" class="form-check-input custom-checkbox me-2" v-model="form.remember">
                        Lembrar-me
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-pink small text-decoration-none hover-underline"
                    >
                        Esqueceu a senha?
                    </Link>
                </div>

                <div class="mt-5">
                    <button
                        type="submit"
                        class="btn btn-pink w-100 fw-bold py-2"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                        {{ form.processing ? 'Entrando...' : 'Entrar no Sistema' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<style scoped>
/* Fundo da tela inteira (usando o roxo escuro do seu layout) */
.login-container {
    min-height: 100vh;
    width: 100vw;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #280a1b; /* Fundo roxo escuro */
    font-family: 'Inter', sans-serif; /* Ou a fonte do seu projeto */
}

/* O Cartão branco central */
.login-card {
    background-color: #ffffff;
    width: 100%;
    max-width: 450px;
    padding: 40px;
    border-radius: 20px;
}

/* O texto do Logo */
.logo-text {
    color: #ff0055;
    font-weight: 800;
    font-size: 2.2rem;
    margin: 0;
    letter-spacing: -1px;
}

/* Estilos de inputs e labels que você já usa */
.form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #666;
    margin-bottom: 6px;
    display: block;
}

.custom-input {
    width: 100%;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding: 12px 15px;
    background-color: #f8f9fa;
    transition: all 0.3s;
    box-sizing: border-box;
}

.custom-input:focus {
    border-color: #ff0055;
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(255, 0, 85, 0.1);
    outline: none;
}

.custom-checkbox:checked {
    background-color: #ff0055;
    border-color: #ff0055;
}

/* Botão rosa do seu tema */
.btn-pink {
    background-color: #ff0055;
    color: white;
    border: none;
    border-radius: 10px;
    transition: all 0.3s;
    font-size: 1rem;
}

.btn-pink:hover:not(:disabled) {
    background-color: #d90049;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 0, 85, 0.3);
}

.btn-pink:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.text-pink {
    color: #ff0055;
}

.hover-underline:hover {
    text-decoration: underline !important;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
