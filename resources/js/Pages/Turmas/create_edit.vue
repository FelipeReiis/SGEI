<script setup>
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSectionLayout from '@/Components/FormSectionLayout.vue'; // O envelope
    import { ref, watch } from 'vue';


    const props = defineProps({
        turma: Object,
        professores: Array,
        cursos: Array,
        niveis: Array
    });

    const formulario = useForm({
        id: props.turma?.id ?? '',
        professor_id: props.turma?.id_pessoa ?? '',
        curso_id: props.turma?.id_curso ?? '',
        grau: props.turma?.grau ?? '',
        horario: props.turma?.horario ?? '',
        nivel_id: '',
    });

    const maskHorario = (v) => {
        v = v.replace(/\D/g, "");
        if (v.length > 4) v = v.slice(0, 4);
        if (v.length > 2) v = v.substring(0, 2) + ":" + v.substring(2);
        return v;
    };


    const cursosFiltrados = ref([]);


    let primeiraCarga = true;

    watch(() => formulario.professor_id, (novoIdProfessor) => {
        // 1. Encontra o professor
        const prof = props.professores.find(p => p.id === novoIdProfessor);

        if (prof && prof.especialidades) {
            // 2. Filtra os cursos
            cursosFiltrados.value = props.cursos.filter(curso =>
                prof.especialidades.includes(String(curso.id))
            );

            // 3. LÓGICA DE EDIÇÃO:
            // Se for a primeira vez que o componente carrega e temos um curso vindo do banco
            if (primeiraCarga && props.registro?.curso_id) {
                formulario.curso_id = props.registro.curso_id;
                primeiraCarga = false; // Desativa para as próximas trocas manuais
            } else if (!primeiraCarga) {
                // Se o usuário trocar o professor manualmente depois, aí sim limpamos o curso
                formulario.curso_id = '';
            }
        } else {
            cursosFiltrados.value = [];
            formulario.curso_id = '';
        }
    }, { immediate: true });

    const niveisFiltrados = ref([]);


    watch(() => formulario.curso_id, (novoIdCurso) => {
        formulario.nivel_id = '';

        if (!novoIdCurso) {
            niveisFiltrados.value = [];
            return;
        }


        niveisFiltrados.value = props.niveis.filter(nivel =>
            String(nivel.id_curso) === String(novoIdCurso)
        );
    }, { immediate: true });

    const enviar = () => {
        const acao = props.turma ? 'put' : 'post'
        const rota = props.turma ? route('turmas.update', props.turma.id) : route('turmas.store')

         formulario[acao](rota);
    }
</script>

<template>
    <MainLayout>
        <form @submit.prevent="enviar">

            <FormSectionLayout titulo="Configuração de Turma">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Professor*</label>
                        <select v-model="formulario.professor_id" class="form-select custom-input">
                            <option value="">Selecione o professor...</option>
                            <option v-for="p in professores" :key="p.id" :value="p.id">
                                {{ p.nome }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Curso*</label>
                        <select
                            v-model="formulario.curso_id"
                            class="form-select custom-input"
                            :disabled="!formulario.professor_id || cursosFiltrados.length === 0"
                        >
                            <option value="">
                                {{ !formulario.professor_id ? 'Escolha um professor' : 'Selecione o curso...' }}
                            </option>

                            <option v-for="c in cursosFiltrados" :key="c.id" :value="c.id">
                                {{ c.nome }}
                            </option>
                        </select>

                        <small v-if="formulario.professor_id && cursosFiltrados.length === 0" class="text-muted">
                            Este professor não tem cursos vinculados.
                        </small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Grau</label>
                        <input type="text" v-model="formulario.grau" class="form-control custom-input">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Horário</label>
                        <input
                            type="text"
                            :value="formulario.horario"
                            @input="formulario.horario = maskHorario($event.target.value)"
                            class="form-control custom-input"
                            maxlength="5"
                            placeholder="00:00"
                        >
                    </div>

                    <div class="col-md-4" v-if="formulario.curso_id == 1 || formulario.curso_id == 2">
                        <label class="form-label">Nível</label>
                       <select
                            v-model="formulario.nivel_id"
                            class="form-select custom-input"
                            :disabled="niveisFiltrados.length === 0"
                        >
                            <option value="">
                                {{ niveisFiltrados.length > 0 ? 'Selecione o nível...' : 'Nenhum nível para este curso' }}
                            </option>

                            <option v-for="n in niveisFiltrados" :key="n.id" :value="n.id">
                                {{ n.nivel }}
                            </option>
                        </select>
                    </div>
                </div>
            </FormSectionLayout>

            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </MainLayout>
</template>
