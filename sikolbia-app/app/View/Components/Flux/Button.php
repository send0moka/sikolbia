<?php

namespace App\View\Components\Flux;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public ?string $type = 'button',
        public ?string $variant = null
    ) {
    }

    public function render()
    {
        return view('components.flux.button');
    }
}
