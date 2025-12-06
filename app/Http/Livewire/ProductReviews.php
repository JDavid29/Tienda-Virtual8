<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Resena;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductReviews extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $productId;
    public $product;
    public $reviews;
    public $userReview;

    protected $listeners = [
        'startCreate' => 'startCreate',
        'startEdit' => 'startEdit',
        'submitFromClient' => 'submit',
        'setRating' => 'setRating',
        'deleteReview' => 'deleteReview',
    ];

    public $editing = false;
    public $editingReviewId = null;

    public $rating = 5;
    public $title;
    public $comment;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Producto::find($productId);
        // reviews will be loaded in render() with pagination
        if (Auth::check()) {
            $this->userReview = Resena::where('producto_id', $productId)->where('user_id', Auth::id())->first();
        }
    }

    public function loadReviews()
    {
        $query = Resena::with('user')->where('producto_id', $this->productId)->orderBy('created_at', 'desc');
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
        $this->reviews = $query->get();
    }

    public function submit()
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para dejar un comentario.',
                'action' => 'review',
            ]);
            return redirect()->route('login.client');
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:2000',
        ]);

        try {
            if ($this->editing && $this->editingReviewId) {
                $rev = Resena::find($this->editingReviewId);
                if ($rev && $rev->user_id == Auth::id()) {
                    $rev->calificacion = $this->rating;
                    $rev->titulo_opinion = $this->title;
                    $rev->comentario = $this->comment;
                    $rev->save();

                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'success',
                        'message' => 'Reseña actualizada.',
                        'action' => 'review',
                    ]);
                }
            } else {
                Resena::create([
                    'producto_id' => $this->productId,
                    'user_id' => Auth::id(),
                    'calificacion' => $this->rating,
                    'titulo_opinion' => $this->title,
                    'comentario' => $this->comment,
                ]);

                $this->dispatchBrowserEvent('notify', [
                    'type' => 'success',
                    'message' => 'Comentario guardado.',
                    'action' => 'review',
                ]);
            }

            // reset state
            $this->rating = 5;
            $this->title = null;
            $this->comment = null;
            $this->editing = false;
            $this->editingReviewId = null;

            // refresh userReview and paginated list
            if (Auth::check()) {
                $this->userReview = Resena::where('producto_id', $this->productId)->where('user_id', Auth::id())->first();
            }
            $this->resetPage();
            $this->dispatchBrowserEvent('closeProductReviewModal');
        } catch (\Throwable $e) {
            Log::error('ProductReviews submit failed: '.$e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo guardar el comentario.',
            ]);
        }
    }

    public function setRating($value)
    {
        $this->rating = (int) $value;
    }

    public function startCreate()
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para dejar un comentario.',
                'action' => 'review',
            ]);
            return redirect()->route('login.client');
        }

        $this->editing = false;
        $this->editingReviewId = null;
        $this->rating = 5;
        $this->title = null;
        $this->comment = null;
        $this->dispatchBrowserEvent('openProductReviewModal');
    }

    public function startEdit()
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para editar tu comentario.',
                'action' => 'review',
            ]);
            return redirect()->route('login.client');
        }

        $rev = Resena::where('producto_id', $this->productId)->where('user_id', Auth::id())->first();
        if (! $rev) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'info',
                'message' => 'Aún no tienes una reseña para este producto.',
            ]);
            return;
        }

        $this->editing = true;
        $this->editingReviewId = $rev->id;
        $this->rating = $rev->calificacion;
        $this->title = $rev->titulo_opinion;
        $this->comment = $rev->comentario;
        $this->dispatchBrowserEvent('openProductReviewModal');
    }

    public function render()
    {
        $query = Resena::with('user')->where('producto_id', $this->productId)->orderBy('created_at', 'desc');
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
        $reviews = $query->paginate(5);

        return view('livewire.product-reviews', [
            'reviews' => $reviews,
        ]);
    }

    public function deleteReview()
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para eliminar tu reseña.',
            ]);
            return;
        }

        try {
            $rev = Resena::where('producto_id', $this->productId)->where('user_id', Auth::id())->first();
            if ($rev) {
                $rev->delete();
                $this->userReview = null;
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'success',
                    'message' => 'Reseña eliminada.',
                ]);
                $this->resetPage();
            } else {
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'info',
                    'message' => 'No se encontró tu reseña.',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('ProductReviews delete failed: '.$e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo eliminar la reseña.',
            ]);
        }
    }
}
