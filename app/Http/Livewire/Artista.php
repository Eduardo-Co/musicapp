<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Artista as ArtistaModel;
use Illuminate\Support\Facades\Storage;

class Artista extends Component
{
    use WithPagination, WithFileUploads;

    public $nome;
    public $genero; 
    public $foto_url;
    public $selectedArtistaId;
    public $searchTerm = '';
    public $isEditing = false;
    public $isCreating = false;
    public $viewingArtista = null;

    protected $rules = [
        'nome' => 'required|string|max:255',
        'genero' => 'required|string|max:50',
        'foto_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    public function view($artistaId)
    {
        $this->viewingArtista = ArtistaModel::findOrFail($artistaId);
    }

    public function closeView()
    {
        $this->viewingArtista = null;
    }


    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        $artistas = ArtistaModel::where('nome', 'like', '%' . $this->searchTerm . '%')
                                ->paginate(10);

        $start = max($artistas->currentPage() - 2, 1);
        $end = min($artistas->currentPage() + 2, $artistas->lastPage());

        return view('livewire.artista', [
            'artistas' => $artistas,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function edit($artistaId)
    {
        $artista = ArtistaModel::findOrFail($artistaId);
        $this->selectedArtistaId = $artista->id;
        $this->nome = $artista->nome;
        $this->genero = $artista->genero; // Corrigido para 'genero'
        $this->foto_url = $artista->foto_url;
        $this->isEditing = true;
        $this->isCreating = false;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->isCreating = true;
    }

    public function save()
    {
        $this->validate();

        $fotoUrl = null;

        if ($this->foto_url) {
            $fotoUrl = $this->foto_url->store('photos', 'public');
        }

        if ($this->isEditing) {
            $artista = ArtistaModel::findOrFail($this->selectedArtistaId);

            if ($artista->foto_url && $fotoUrl && $artista->foto_url !== $fotoUrl) {
                Storage::disk('public')->delete($artista->foto_url);
            }

            $artista->update([
                'nome' => $this->nome,
                'genero' => $this->genero,
                'foto_url' => $fotoUrl ?? $artista->foto_url,
            ]);

            session()->flash('message', 'Artista atualizado com sucesso.');
        } elseif ($this->isCreating) {
            ArtistaModel::create([
                'nome' => $this->nome,
                'genero' => $this->genero,
                'foto_url' => $fotoUrl,
            ]);

            session()->flash('message', 'Artista criado com sucesso.');
        }

        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->nome = '';
        $this->genero = ''; 
        $this->foto_url = null;
        $this->selectedArtistaId = null;
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function delete($artistaId)
    {
        $artista = ArtistaModel::findOrFail($artistaId);


        if ($artista->albums()->exists()) {
            session()->flash('message-deleted', 'O artista não pode ser deletado porque está relacionado a um ou mais álbuns.');
        }else{

            if ($artista->foto_url) {
                Storage::disk('public')->delete($artista->foto_url);
            }
            $artista->delete();
            session()->flash('message-deleted', 'Artista deletado com sucesso.');
        }
    }
}
