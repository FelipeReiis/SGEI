<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Define o MainLayout para rodar dentro do layout principal do sistema
defineOptions({ layout: MainLayout });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Novo Usuário" />

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card-custom shadow-sm p-4">

                <!-- Cabeçalho do Card -->
                <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                    <span class="fs-4">👤</span>
                    <h5 class="fw-bold mb-0 text-secondary">Cadastrar Novo Usuário</h5>
                </div>

                <form @submit.prevent="submit">
                    <!-- Campo Nome -->
                    <div class="mb-3">
                        <InputLabel for="name" value="Nome Completo*" class="form-label" />

                        <TextInput
                            id="name"
                            type="text"
                            class="form-control custom-input"
                            :class="{ 'is-invalid': form.errors.name }"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Digite o nome..."
                        />

                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <!-- Campo Email -->
                    <div class="mb-3">
                        <InputLabel for="email" value="E-mail*" class="form-label" />

                        <TextInput
                            id="email"
                            type="email"
                            class="form-control custom-input"
                            :class="{ 'is-invalid': form.errors.email }"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="exemplo@email.com"
                        />

                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <!-- Campo Senha -->
                    <div class="mb-3">
                        <InputLabel for="password" value="Senha*" class="form-label" />

                        <TextInput
                            id="password"
                            type="password"
                            class="form-control custom-input"
                            :class="{ 'is-invalid': form.errors.password }"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />

                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <!-- Campo Confirmar Senha -->
                    <div class="mb-4">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirmar Senha*"
                            class="form-label"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="form-control custom-input"
                            :class="{ 'is-invalid': form.errors.password_confirmation }"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />

                        <InputError
                            class="mt-1"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <!-- Ações / Botões -->
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <Link
                            :href="route('login')"
                            class="text-decoration-none small text-muted hover-pink"
                        >
                            Já tem uma conta?
                        </Link>

                        <button
                            type="submit"
                            class="btn btn-pink px-4 fw-bold"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Cadastrando...' : 'Cadastrar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hover-pink:hover {
    color: #ff0055 !important;
}
</style>
