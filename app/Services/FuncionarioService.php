<?php

namespace App\Services;

use App\Models\pessoa;
use App\Models\Professor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuncionarioService
{

    private $enderecoService;
    private $pessoasService;
    public function __construct(EnderecoService $enderecoService, PessoasService $pessoasService)
    {
        $this->enderecoService = $enderecoService;
        $this->pessoasService = $pessoasService;
    }

    public function index(Request $req)
    {
        try{

            $funcionarios = pessoa::select('nome', 'cpf', 'id')->where('funcionario', 1);

            if ($req->busca) {
                $funcionarios->where('nome', 'ILIKE', '%' . $req->busca . '%');
            }

            return $funcionarios;

        }catch(Exception $e){
            throw new Exception ("Houve um erro ao carregar os funcionarios: " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {

            $funcionario = Pessoa::with(['endereco', 'bancario', 'professor'])->find($id);
            $especialidadesIds = $funcionario->professor ? $funcionario->professor->pluck('id_especialidade')->toArray() : [];
            return [$funcionario, $especialidadesIds];

        } catch (Exception $e) {
            throw new Exception ("houve um erro ao buscar o funcionario: " . $e->getMessage());
        }
    }

    public function createProfessor($idPessoa, $idEspecialidade){
        try{
            foreach($idEspecialidade as $espec){
                Professor::create([
                    'id_pessoa' => $idPessoa,
                    'id_especialidade' => $espec
                ]);
            }
        }catch(Exception $e){
            throw new Exception ("Houve um erro ao criar o Professor: " . $e->getMessage());

        }
    }

    public function updateProfessor($idPessoa, $idEspecialidade){
        try{

                $delete = Professor::where('id_pessoa', $idPessoa)->delete();
                if($delete){
                    foreach($idEspecialidade as $espec){
                        Professor::create([
                            'id_pessoa' => $idPessoa,
                            'id_especialidade' => $espec
                        ]);
                    }
                }

        }catch(Exception $e){
            throw new Exception ("Houve um erro ao atualizar as especialidades: " . $e->getMessage());
        }
    }


}
