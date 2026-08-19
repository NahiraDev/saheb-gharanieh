<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $cards = Category::query()
            ->active()
            ->onLanding()
            ->get();

        return view('home', [
            'cards' => $cards,
            'settings' => Setting::map(),
        ]);
    }
}
