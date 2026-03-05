<script setup>
defineProps({
    modelValue: Object, // O objeto (aluno ou responsavel)
    titulo: String,
    icon: String,
    erros: Object,      // Para mostrar validações do Laravel
    mostrarEndereco: { type: Boolean, default: false }
});
</script>

<template>
  <div class="form-section mb-4">
    <h5 class="section-title text-pink fw-bold">
        <span class="icon">{{ icon }}</span> {{ titulo }}
    </h5>
    <hr class="mb-4">

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Nome Completo*</label>
        <input type="text" v-model="modelValue.nome" class="form-control custom-input" :class="{'is-invalid': erros?.nome}">
      </div>
      <div class="col-md-4" v-if="!mostrarEndereco"> <slot name="extra-field"></slot>
      </div>

      <div class="col-md-4">
        <label class="form-label">E-mail</label>
        <input type="email" v-model="modelValue.email" class="form-control custom-input">
      </div>
      <div class="col-md-4">
        <label class="form-label">Telefone</label>
        <input type="text" v-model="modelValue.telefone" class="form-control custom-input">
      </div>

      <div class="col-md-4">
        <label class="form-label">RG*</label>
        <input type="text" v-model="modelValue.rg" class="form-control custom-input">
      </div>
      <div class="col-md-4">
        <label class="form-label">CPF*</label>
        <input type="text" v-model="modelValue.cpf" class="form-control custom-input">
      </div>
      <div class="col-md-4">
        <label class="form-label">Data de Nascimento*</label>
        <input type="date" v-model="modelValue.data_nascimento" class="form-control custom-input">
      </div>
    </div>

    <div v-if="mostrarEndereco" class="row g-3 mt-2">
      <div class="col-md-3">
        <label class="form-label">CEP</label>
        <input type="text" v-model="modelValue.cep" class="form-control custom-input">
      </div>
      <div class="col-md-4">
        <label class="form-label">Logradouro</label>
        <input type="text" v-model="modelValue.logradouro" class="form-control custom-input">
      </div>
      <div class="col-md-4">
        <label class="form-label">Bairro</label>
        <input type="text" v-model="modelValue.bairro" class="form-control custom-input">
      </div>
      <div class="col-md-3">
        <label class="form-label">Número</label>
        <input type="text" v-model="modelValue.numero" class="form-control custom-input">
      </div>
      <div class="col-md-4">
        <label class="form-label">Complemento</label>
        <input type="text" v-model="modelValue.complemento" class="form-control custom-input">
      </div>
    </div>
  </div>
</template>
