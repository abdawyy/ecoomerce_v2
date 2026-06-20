<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomPagination extends Component
{
    public LengthAwarePaginator $paginator;
    public array $elements;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
        $this->elements = $this->generateElements();
    }

    private function generateElements(): array
    {
        return $this->paginator->toArray()['links'];
    }

    public function render()
    {
        return view('components.custom-pagination');
    }
}
