<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class nav extends Component
{
    public $items;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->items = $this->prepareItems(config('nav'));
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav');
    }

    /**
     * Remove items from the navigation that the user does not have access to.
     *
     * @param array $items
     * @return array
     */
    protected function prepareItems($items)
    {
        $user = Auth::user();
        foreach ($items as $key => $item) {
            if (isset($item['ability']) && !$user->can($item['ability'], $item['model'] ?? null)) {
                unset($items[$key]);
            }
        }
        return $items;
    }
}
