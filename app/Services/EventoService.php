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
                $precoLimpo = str_replace(['R$ ', '.'], '', $req->preco);
                $req->preco = (float) str_replace(',', '.', $precoLimpo);
                $evento = Evento::where('nome', $req->evento)->where('valor',  $req->preco)->where('data', Carbon::parse($req->data_evento)->format('Y-m-d'))->first();
                if($evento){
                   return "Já existe um evento com os mesmos dados.";
                }
                DB::beginTransaction();
                if($req->hasFile('img')){
                    $arquivo = $req->file('img');
                    $nomeNovo = str_replace(' ', '-',$req->evento).'-'.Carbon::now()->format('Y-m-d').$arquivo->getClientOriginalName();
                    $arquivo->storeAs('eventos_imgs', $nomeNovo, 'public');
                    $caminho = 'eventos_imgs/' . $nomeNovo;
                }


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

                    $arquivo->storeAs('eventos_imgs', $nomeNovo, 'public');
                    $caminho =  'eventos_imgs/' . $nomeNovo;
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
               $evento = Evento::findOrFail($id);

                if ($evento->imagem) {
                    $caminhoNoStorage = str_replace('storage/', '', $evento->imagem);

                    // 3. Deleta o arquivo físico do disco público do Docker
                    if (Storage::disk('public')->exists($caminhoNoStorage)) {
                        Storage::disk('public')->delete($caminhoNoStorage);
                    }
                }
                $evento->delete();

                DB::commit();

                return 'Evento deletado com sucesso.';
            }catch(Exception $e){
                throw new Exception ("Houve um erro ao excluir o evento: " . $e->getMessage());
            }
        }

    }
