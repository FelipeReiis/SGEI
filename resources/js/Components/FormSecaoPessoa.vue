<script setup>
    import { maskCPF, maskTelefone, maskCEP } from '@/utils/mascaras';
    import { buscarCep } from '@/utils/cepService';
    import {watch} from 'vue';

    const props = defineProps({
        modelValue: Object, // O objeto (aluno ou responsavel)
        titulo: String,
        icon: String,
        erros: Object,      // Para mostrar validações do Laravel
        prefixo: String, //mostrar os erros para cada tipo de formulario
        mostrarPessoa: { type: Boolean, default: false },
        mostrarEndereco: { type: Boolean, default: false },
        mostrarBanco: { type: Boolean, default: false }
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

    watch(() => props.modelValue.cpf, (novoValor) => {
        if (novoValor && novoValor.length <= 11) { // Só mascara se for o dado puro do banco
            props.modelValue.cpf = maskCPF(novoValor);
        }
    }, { immediate: true });

    watch(() => props.modelValue.telefone, (novoValor) => {
        if (novoValor && novoValor.length <= 11) {
            props.modelValue.telefone = maskTelefone(novoValor);
        }
    }, { immediate: true });
</script>

<template>
  <div class="form-section mb-4">
    <h5 class="section-title text-pink fw-bold">
        <span class="icon">{{ icon }}</span> {{ titulo }}
    </h5>
    <hr class="mb-4">

    <div class="row g-3" v-if="mostrarPessoa">
      <div class="col-md-4">
        <label class="form-label">Nome Completo*</label>
        <input type="text" v-model="modelValue.nome" class="form-control custom-input" :class="{'is-invalid': erros?.[`${prefixo}.nome`]}" >
        <div v-if="erros?.[`${prefixo}.nome`]" class="invalid-feedback">
            {{ erros[`${prefixo}.nome`] }}
         </div>
      </div>
      <div class="col-md-4" v-if="!mostrarEndereco"> <slot name="extra-field"></slot>
      </div>

      <div class="col-md-4">
        <label class="form-label">E-mail</label>
        <input type="email" v-model="modelValue.email" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.email`]}" >
        <div v-if="erros?.[`${prefixo}.email`]" class="invalid-feedback">
            {{ erros[`${prefixo}.email`] }}
         </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">Telefone</label>
        <input type="text" @input="modelValue.telefone = maskTelefone($event.target.value)" class="form-control custom-input" :class="{'is-invalid': erros?.[`${prefixo}.telefone`]}" >
        <div v-if="erros?.[`${prefixo}.telefone`]" class="invalid-feedback">
            {{ erros[`${prefixo}.telefone`] }}
         </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">RG*</label>
        <input type="text" v-model="modelValue.rg" class="form-control custom-input" :class="{'is-invalid': erros?.[`${prefixo}.rg`]}" >
        <div v-if="erros?.[`${prefixo}.rg`]" class="invalid-feedback">
            {{ erros[`${prefixo}.rg`] }}
         </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">CPF*</label>
        <input type="text" @input="modelValue.cpf = maskCPF($event.target.value)" class="form-control custom-input" :value="modelValue.cpf"  maxlength="14"  :class="{'is-invalid': erros?.[`${prefixo}.cpf`]}" >
        <div v-if="erros?.[`${prefixo}.cpf`]" class="invalid-feedback">
            {{ erros[`${prefixo}.cpf`] }}
         </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">Data de Nascimento*</label>
        <input type="date" v-model="modelValue.data_nascimento" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.data_nascimento`]}" >
        <div v-if="erros?.[`${prefixo}.data_nascimento`]" class="invalid-feedback">
            {{ erros[`${prefixo}.data_nascimento`] }}
         </div>
      </div>
    </div>

    <div v-if="mostrarEndereco" class="row g-3 mt-2">
      <div class="col-md-3">
        <label class="form-label">CEP</label>
        <input type="text" @input="modelValue.cep = maskCEP($event.target.value)" class="form-control custom-input" :value="modelValue.cep" maxlength="9"  :class="{'is-invalid': erros?.[`${prefixo}.cep`]}" >
        <div v-if="erros?.[`${prefixo}.cep`]" class="invalid-feedback">
            {{ erros[`${prefixo}.cep`] }}
         </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Logradouro</label>
        <input type="text" v-model="modelValue.logradouro" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.logradouro`]}" >
        <div v-if="erros?.[`${prefixo}.logradouro`]" class="invalid-feedback">
            {{ erros[`${prefixo}.logradouro`] }}
         </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bairro</label>
        <input type="text" v-model="modelValue.bairro" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.bairro`]}" >
        <div v-if="erros?.[`${prefixo}.bairro`]" class="invalid-feedback">
            {{ erros[`${prefixo}.bairro`] }}
         </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Número</label>
        <input type="text" v-model="modelValue.numero" class="form-control custom-input" :class="{'is-invalid': erros?.[`${prefixo}.numero`]}" >
        <div v-if="erros?.[`${prefixo}.numero`]" class="invalid-feedback">
            {{ erros[`${prefixo}.numero`] }}
         </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Complemento</label>
        <input type="text" v-model="modelValue.complemento" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.complemento`]}" >
        <div v-if="erros?.[`${prefixo}.complemento`]" class="invalid-feedback">
            {{ erros[`${prefixo}.complemento`] }}
         </div>
      </div>
    </div>

    <div v-if="mostrarBanco" class="row g-3 mt-2">
      <div class="col-md-3">
        <label class="form-label">Agencia</label>
        <input type="text" v-model="modelValue.agencia" class="form-control custom-input" maxlength="9"  :class="{'is-invalid': erros?.[`${prefixo}.agencia`]}" >
        <div v-if="erros?.[`${prefixo}.agencia`]" class="invalid-feedback">
            {{ erros[`${prefixo}.agencia`] }}
         </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Conta</label>
        <input type="text" v-model="modelValue.conta" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.conta`]}" >
        <div v-if="erros?.[`${prefixo}.conta`]" class="invalid-feedback">
            {{ erros[`${prefixo}.conta`] }}
         </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Chave Pix</label>
        <input type="text" v-model="modelValue.pix" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.pix`]}" >
        <div v-if="erros?.[`${prefixo}.pix`]" class="invalid-feedback">
            {{ erros[`${prefixo}.pix`] }}
         </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Banco</label>
        <input type="text" v-model="modelValue.banco" class="form-control custom-input"  :class="{'is-invalid': erros?.[`${prefixo}.banco`]}" >
        <div v-if="erros?.[`${prefixo}.banco`]" class="invalid-feedback">
            {{ erros[`${prefixo}.banco`] }}
         </div>
      </div>
    </div>
  </div>
</template>
