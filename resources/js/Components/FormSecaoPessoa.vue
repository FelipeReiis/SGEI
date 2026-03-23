<script setup>
    import { maskCPF, maskTelefone, maskCEP } from '@/utils/mascaras';
    import { buscarCep } from '@/utils/cepService';
    import {watch} from 'vue';

    const props = defineProps({
        modelValue: Object, // O objeto (aluno ou responsavel)
        titulo: String,
        icon: String,
        erros: Object,      // Para mostrar validações do Laravel
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
        <input type="text" v-model="modelValue.nome" class="form-control custom-input" :class="{'is-invalid': erros?.nome}" required>
        <div v-if="erros?.nome" class="invalid-feedback">
            {{ erros.nome }}
         </div>
      </div>
      <div class="col-md-4" v-if="!mostrarEndereco"> <slot name="extra-field"></slot>
      </div>

      <div class="col-md-4">
        <label class="form-label">E-mail</label>
        <input type="email" v-model="modelValue.email" class="form-control custom-input" :class="{'is-invalid': erros?.email}">
        <div v-if="erros?.email" class="invalid-feedback">
            {{ erros.email }}
        </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">Telefone</label>
        <input type="text" @input="modelValue.telefone = maskTelefone($event.target.value)" class="form-control custom-input" :value="modelValue.telefone" maxlength="15" :class="{'is-invalid': erros?.telefone}">
        <div v-if="erros?.telefone" class="invalid-feedback">
            {{ erros.telefone }}
        </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">RG*</label>
        <input type="text" v-model="modelValue.rg" class="form-control custom-input" :class="{'is-invalid': erros?.rg}" required>
        <div v-if="erros?.rg" class="invalid-feedback">
            {{ erros.rg }}
        </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">CPF*</label>
        <input type="text" @input="modelValue.cpf = maskCPF($event.target.value)" class="form-control custom-input" :value="modelValue.cpf"  maxlength="14" :class="{'is-invalid': erros?.cpf}" required>
        <div v-if="erros?.cpf" class="invalid-feedback">
            {{ erros.cpf }}
        </div>
      </div>

      <div class="col-md-4">
        <label class="form-label">Data de Nascimento*</label>
        <input type="date" v-model="modelValue.data_nascimento" class="form-control custom-input" :class="{'is-invalid': erros?.data_nascimento}" required>
        <div v-if="erros?.data_nascimento" class="invalid-feedback">
            {{ erros.data_nascimento }}
        </div>
      </div>
    </div>

    <div v-if="mostrarEndereco" class="row g-3 mt-2">
      <div class="col-md-3">
        <label class="form-label">CEP</label>
        <input type="text" @input="modelValue.cep = maskCEP($event.target.value)" class="form-control custom-input" :value="modelValue.cep" maxlength="9" :class="{'is-invalid': erros?.cep}" required>
        <div v-if="erros?.cep" class="invalid-feedback">
            {{ erros.cep }}
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Logradouro</label>
        <input type="text" v-model="modelValue.logradouro" class="form-control custom-input" :class="{'is-invalid': erros?.logradouro}" required>
        <div v-if="erros?.logradouro" class="invalid-feedback">
            {{ erros.logradouro }}
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Bairro</label>
        <input type="text" v-model="modelValue.bairro" class="form-control custom-input" :class="{'is-invalid': erros?.bairro}" required>
        <div v-if="erros?.bairro" class="invalid-feedback">
            {{ erros.bairro }}
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Número</label>
        <input type="text" v-model="modelValue.numero" class="form-control custom-input" :class="{'is-invalid': erros?.numero}" >
        <div v-if="erros?.numero" class="invalid-feedback">
            {{ erros.numero }}
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Complemento</label>
        <input type="text" v-model="modelValue.complemento" class="form-control custom-input" :class="{'is-invalid': erros?.complemento}" required>
        <div v-if="erros?.complemento" class="invalid-feedback">
            {{ erros.complemento }}
        </div>
      </div>
    </div>

    <div v-if="mostrarBanco" class="row g-3 mt-2">
      <div class="col-md-3">
        <label class="form-label">Agencia</label>
        <input type="text" v-model="modelValue.agencia" class="form-control custom-input" maxlength="9" :class="{'is-invalid': erros?.agencia}" required>
        <div v-if="erros?.agencia" class="invalid-feedback">
            {{ erros.agencia }}
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Conta</label>
        <input type="text" v-model="modelValue.conta" class="form-control custom-input" :class="{'is-invalid': erros?.conta}" required>
        <div v-if="erros?.conta" class="invalid-feedback">
            {{ erros.conta }}
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Chave Pix</label>
        <input type="text" v-model="modelValue.pix" class="form-control custom-input" :class="{'is-invalid': erros?.pix}" required>
        <div v-if="erros?.pix" class="invalid-feedback">
            {{ erros.pix }}
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Banco</label>
        <input type="text" v-model="modelValue.banco" class="form-control custom-input" :class="{'is-invalid': erros?.banco}" >
        <div v-if="erros?.banco" class="invalid-feedback">
            {{ erros.banco }}
        </div>
      </div>
    </div>
  </div>
</template>
