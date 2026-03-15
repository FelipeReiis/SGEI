<script setup>
    import { maskCPF, maskTelefone, maskCEP } from '@/utils/mascaras';
    import { buscarCep } from '@/utils/cepService';
    import {watch} from 'vue';

    const props = defineProps({
        modelValue: Object, // O objeto (aluno ou responsavel)
        titulo: String,
        icon: String,
        erros: Object,      // Para mostrar validações do Laravel
        mostrarEndereco: { type: Boolean, default: false }
    });

    watch(() => props.modelValue.cep, async(novoCep)=>{
        const limpo = novoCep.replace(/\D/g, '');

        if(limpo.length === 8){
            const resultado = await buscarCep(limpo);

            if(resultado){
                props.modelValue.logradouro  = resultado.logradouro;
                props.modelValue.bairro = resultado.bairro;
                props.modelValue.complemento = resultado.complemento;
            }
        }
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
        <input type="text" v-model="modelValue.nome" class="form-control custom-input" :class="{'is-invalid': erros?.nome}" required>
      </div>
      <div class="col-md-4" v-if="!mostrarEndereco"> <slot name="extra-field"></slot>
      </div>

      <div class="col-md-4">
        <label class="form-label">E-mail</label>
        <input type="email" v-model="modelValue.email" class="form-control custom-input" :class="{'is-invalid': erros?.email}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Telefone</label>
        <input type="text" @input="modelValue.telefone = maskTelefone($event.target.value)" class="form-control custom-input" :value="modelValue.telefone" maxlength="15" :class="{'is-invalid': erros?.telefone}">
      </div>

      <div class="col-md-4">
        <label class="form-label">RG*</label>
        <input type="text" v-model="modelValue.rg" class="form-control custom-input" :class="{'is-invalid': erros?.rg}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">CPF*</label>
        <input type="text" @input="modelValue.cpf = maskCPF($event.target.value)" class="form-control custom-input" :value="modelValue.cpf"  maxlength="14" :class="{'is-invalid': erros?.cpf}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Data de Nascimento*</label>
        <input type="date" v-model="modelValue.data_nascimento" class="form-control custom-input" :class="{'is-invalid': erros?.data_nascimento}" required>
      </div>
    </div>

    <div v-if="mostrarEndereco" class="row g-3 mt-2">
      <div class="col-md-3">
        <label class="form-label">CEP</label>
        <input type="text" @input="modelValue.cep = maskCEP($event.target.value)" class="form-control custom-input" :value="modelValue.cep" maxlength="9" :class="{'is-invalid': erros?.cep}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Logradouro</label>
        <input type="text" v-model="modelValue.logradouro" class="form-control custom-input" :class="{'is-invalid': erros?.logradouro}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bairro</label>
        <input type="text" v-model="modelValue.bairro" class="form-control custom-input" :class="{'is-invalid': erros?.bairro}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Número</label>
        <input type="text" v-model="modelValue.numero" class="form-control custom-input" :class="{'is-invalid': erros?.numero}" >
      </div>
      <div class="col-md-4">
        <label class="form-label">Complemento</label>
        <input type="text" v-model="modelValue.complemento" class="form-control custom-input" :class="{'is-invalid': erros?.complemento}" required>
      </div>
    </div>
  </div>
</template>
