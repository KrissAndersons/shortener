<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function shorten(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url']
        ]);

        $shortCode = Str::random(6);

        $link = Link::create([
            'original_url' => $request->url,
            'short_code' => $shortCode,
        ]);

        return response()->json([
            'short_url' => url($shortCode),
            'short_code' => $shortCode,
        ]);
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();

        return redirect($link->original_url);
    }
}
