<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function shorten(Request $request)
    {
        try {
            $request->validate([
                'url' => ['required', 'url']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Please provide a valid URL.'
            ], 422);
        }

        try {
            $shortCode = Str::random(6);

            $link = Link::create([
                'original_url' => $request->url,
                'short_code' => $shortCode,
                'click_count' => 0,
            ]);

            return response()->json([
                'short_url' => url($shortCode),
                'short_code' => $shortCode,
                'link' => [
                    'id' => $link->id,
                    'original_url' => $link->original_url,
                    'short_code' => $link->short_code,
                    'click_count' => $link->click_count,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Unable to save the link. Please check the database connection.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        return response()->json(
            Link::latest()->get(['id', 'original_url', 'short_code', 'click_count'])
        );
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();
        $link->increment('click_count');

        return redirect($link->original_url);
    }
}
