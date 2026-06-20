<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    /**
     * Create a new component instance.
     */ public $headers;
    public $rows;

    public $url;

    public function __construct($headers, $rows , $url)
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->url=$url;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data-table');
    }
}
