<?php

namespace App\View\Components\Flux;

use Illuminate\View\Component;

class Card extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('components.flux.card');
    }
}
