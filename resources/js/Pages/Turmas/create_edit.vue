<script setup>
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSectionLayout from '@/Components/FormSectionLayout.vue'; // O envelope
    import { ref, watch, computed, onMounted } from 'vue';


    const props = defineProps({
        turma: Object,
        professores: Array,
        cursos: Array,
        niveis: Array,
        alunos: Array
    });

    const formulario = useForm({
        id: props.turma?.id ?? '',
        professor_id: props.turma?.id_pessoa ?? '',
        curso_id: props.turma?.id_curso ?? '',
        grau: props.turma?.grau ?? '',
        horario: props.turma?.horario ?? '',
        nivel_id: props.turma?.id_nivel ?? '',
        alunos_ids: [],
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
    let primeiraCargaNivel = true; // Trava para a edição

    watch(() => formulario.curso_id, (novoIdCurso) => {
    // Se o curso for desmarcado, limpa tudo
        if (!novoIdCurso) {
            niveisFiltrados.value = [];
            formulario.nivel_id = '';
            return;
        }

        // 1. Filtro com conversão forçada para String
        niveisFiltrados.value = props.niveis.filter(nivel =>
            String(nivel.id_curso) === String(novoIdCurso)
        );

        // 2. Lógica de Edição
        if (primeiraCargaNivel && props.registro?.nivel_id) {
            // Forçamos o valor do banco para dentro do v-model
            formulario.nivel_id = props.registro.nivel_id;
            primeiraCargaNivel = false;
        } else if (!primeiraCargaNivel) {
            // Só limpa o campo se NÃO for a carga inicial (mudança manual do usuário)
            formulario.nivel_id = '';
        }
    }, { immediate: true });

    const buscaAluno = ref('');
    const primeiraCargaAlunos = ref(true);

    const alunosFiltrados = computed(() => {
        if (!buscaAluno.value) return props.alunos;
        const termo = buscaAluno.value.toLowerCase();
        return props.alunos.filter(aluno =>
            aluno.aluno_nome.toLowerCase().includes(termo) ||
            aluno.cpf.includes(termo)
        );
    });

    onMounted(() => {
        if (props.turma && props.alunos) {
            // Filtramos os alunos que possuem o ID da turma atual
            // Substitua 'id_turma' pelo nome real da chave que vem no seu JSON
            const matriculados = props.alunos
                .filter(aluno => Number(aluno.id_turma) === Number(props.turma.id))
                .map(aluno => aluno.aluno_id);

            formulario.alunos_ids = matriculados;
        }
    });

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
                        <select v-model="formulario.professor_id" class="form-select custom-input" :class="{'is-invalid' : formulario.errors && formulario.errors['professor_id']}">
                            <option value="">Selecione o professor...</option>
                            <option v-for="p in professores" :key="p.id" :value="p.id">
                                {{ p.nome }}
                            </option>
                        </select>
                        <div v-if="formulario.errors['professor_id']" class="invalid-feedback">
                            {{ formulario.errors['professor_id'] }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Curso*</label>
                        <select
                            v-model="formulario.curso_id"
                            class="form-select custom-input"
                            :class="{'is-invalid' : formulario.errors && formulario.errors['curso_id']}"
                            :disabled="!formulario.professor_id || cursosFiltrados.length === 0"
                        >
                            <option value="">
                                {{ !formulario.professor_id ? 'Escolha um professor' : 'Selecione o curso...' }}
                            </option>

                            <option v-for="c in cursosFiltrados" :key="c.id" :value="c.id">
                                {{ c.nome }}
                            </option>
                        </select>
                        <div v-if="formulario.errors['curso_id']" class="invalid-feedback">
                            {{ formulario.errors['curso_id'] }}
                        </div>

                        <small v-if="formulario.professor_id && cursosFiltrados.length === 0" class="text-muted">
                            Este professor não tem cursos vinculados.
                        </small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Grau</label>
                        <input type="text" v-model="formulario.grau" class="form-control custom-input" :class="{'is-invalid' : formulario.errors && formulario.errors['grau']}" maxlength="25">
                        <div v-if="formulario.errors['grau']" class="invalid-feedback">
                            {{ formulario.errors['grau'] }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Horário</label>
                        <input
                            type="text"
                            :value="formulario.horario"
                            @input="formulario.horario = maskHorario($event.target.value)"
                            class="form-control custom-input"
                            :class="{'is-invalid' : formulario.errors && formulario.errors['horario']}"
                            maxlength="5"
                            placeholder="00:00"
                        >
                        <div v-if="formulario.errors['horario']" class="invalid-feedback">
                            {{ formulario.errors['horario'] }}
                        </div>
                    </div>

                    <div class="col-md-4" v-if="['1', '2', 1, 2].includes(formulario.curso_id)">
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
                                {{ n.nivel }} </option>
                        </select>
                    </div>
                </div>
            </FormSectionLayout>

            <button type="submit" class="btn btn-success">{{ props.turma ? 'Atualizar' : 'Salvar' }}</button>

            <FormSectionLayout titulo="Alunos da Turma" icon="👥" class="mt-3">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-pink text-white border-0">🔍</span>
                            <input
                                type="text"
                                v-model="buscaAluno"
                                class="form-control custom-input"
                                placeholder="Buscar aluno por nome ou CPF..."
                            >
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="list-group border rounded shadow-sm" style="max-height: 400px; overflow-y: auto;">

                            <label
                                v-for="aluno in alunosFiltrados"
                                :key="aluno.aluno_id"
                                class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3"
                                :class="{'bg-light': formulario.alunos_ids.includes(aluno.aluno_id)}"
                                style="cursor: pointer;"
                            >
                                <input
                                    class="form-check-input me-3"
                                    type="checkbox"
                                    :value="aluno.aluno_id"
                                    v-model="formulario.alunos_ids"
                                    :id="'aluno_' + aluno.aluno_id"
                                >
                                <div class="d-flex justify-content-between w-100 align-items-center">
                                    <div>
                                        <span class="d-block fw-bold" :class="formulario.alunos_ids.includes(aluno.aluno_id) ? 'text-pink' : 'text-dark'">
                                            {{ aluno.aluno_nome }}
                                        </span>
                                        <small class="text-muted">CPF: {{ aluno.aluno_cpf }}</small>
                                    </div>
                                    <span v-if="formulario.alunos_ids.includes(aluno.aluno_id)" class="badge bg-pink text-white rounded-pill">
                                        Selecionado
                                    </span>
                                </div>
                            </label>

                            <div v-if="alunosFiltrados.length === 0" class="p-4 text-center text-muted">
                                Nenhum aluno encontrado para esta busca.
                            </div>
                        </div>

                        <div class="mt-2 text-end">
                            <small class="text-muted">
                                Total: <strong>{{ formulario.alunos_ids.length }}</strong> aluno(s) nesta turma.
                            </small>
                        </div>
                    </div>
                </div>
            </FormSectionLayout>
        </form>
    </MainLayout>
</template>
