<?php
    namespace App\Services;

    use App\Models\Evento;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;

    class EventoService{


        public function store(Request $req){
            try{
                DB::beginTransaction();
                if($req->hasFile('img')){
                    $arquivo = $req->file('img');
                    $nomeNovo = str_replace(' ', '-',$req->evento).'-'.Carbon::now()->format('Y-m-d').$arquivo->getClientOriginalName();
                    $caminho = $req->file('img')->storeAs('eventos_imgs', $nomeNovo, 'public');
                }

                $precoLimpo = str_replace(['R$ ', '.'], '', $req->preco);
                $req->preco = (float) str_replace(',', '.', $precoLimpo);

                Evento::create([
                    'nome' => $req->evento,
                    'valor' => $req->preco,
                    'data' => $req->data_evento,
                    'imagem' => $caminho ?? null,
                    'observacao' => $req->obs,
                    'status' => $req->ativo
                ]);
                DB::commit();
                return 'Evento cadastrado com sucesso!';
            }catch(Exception $e){
                dd($e);
                DB::rollback();
                throw new Exception ("Houve um problema ao cadastrar o evento: " . $e->getMessage());
            }
        }

        public function edit($id){
            try{
                $evento = Evento::find($id);
                $evento->imagem = $evento->imagem ? Storage::url($evento->imagem) : null;
                return $evento;
            }catch(Exception $e){
                throw new Exception ("Houve um problema ao resgatar os dados do evento: " . $e->getMessage());
            }
        }

        public function update(Request $req, $id)
        {
            try {
                DB::beginTransaction();
                $evento = Evento::findOrFail($id);

                // Mantém a imagem antiga por padrão
                $caminho = $evento->imagem;

                // Se enviou nova imagem
                if ($req->hasFile('img')) {


                    if ($evento->imagem && Storage::disk('public')->exists($evento->imagem)) {
                        Storage::disk('public')->delete($evento->imagem);
                    }

                    $arquivo = $req->file('img');

                    $nomeNovo = str_replace(' ', '-',$req->evento).'-'.now()->timestamp . '.' . $arquivo->getClientOriginalExtension();

                    $caminho = $arquivo->storeAs('eventos_imgs', $nomeNovo, 'public');
                }

                $precoLimpo = str_replace(['R$ ', '.'], '', $req->preco);
                $req->preco = (float) str_replace(',', '.', $precoLimpo);

                $evento->update([
                    'nome' => $req->evento,
                    'valor' => $req->preco,
                    'data' => $req->data_evento,
                    'imagem' => $caminho,
                    'observacao' => $req->obs,
                    'status' => $req->ativo
                ]);

                DB::commit();

                return 'Evento atualizado com sucesso!';
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception ("Houve um problema ao atualizar o evento: " . $e->getMessage());
            }
        }

        public function delete($id){
            try{
                Evento::where('id', $id)->delete();

                return 'Evento deletado com sucesso.';
            }catch(Exception $e){
                throw new Exception ("Houve um erro ao excluir o evento: " . $e->getMessage());
            }
        }

    }
