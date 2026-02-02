<?php

namespace App\View\Components\Flux;

use Illuminate\View\Component;

class Link extends Component
{
    public function __construct(
        public ?string $href = null,
        public ?string $variant = null
    ) {
    }

    public function render()
    {
        return view('components.flux.link');
    }
}
