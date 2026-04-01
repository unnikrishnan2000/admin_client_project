<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UiBlock;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class UiBlockController extends Controller
{
    public function welcome()
    {
        $blocks = UiBlock::orderBy('order')->get();

        return Inertia::render('Welcome', [
            'blocks' => $blocks,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function index()
    {
        $blocks = UiBlock::orderBy('order')->get();

        return Inertia::render('Admin/UiBlocks', [
            'blocks' => $blocks,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:banner,card,list,stats',
            'status' => 'required|boolean',
            'order' => 'required|integer|min:1',
            'config' => 'nullable|array',
        ]);

        UiBlock::create($data);

        return back();
    }

    public function update(Request $request, UiBlock $uiBlock)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:banner,card,list,stats',
            'status' => 'required|boolean',
            'order' => 'required|integer|min:1',
            'config' => 'nullable|array',
        ]);

        $uiBlock->update($data);

        return back();
    }

    public function destroy(UiBlock $uiBlock)
    {
        $uiBlock->delete();

        return back();
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:ui_blocks,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            UiBlock::where('id', $id)->update(['order' => $index + 1]);
        }

        return back();
    }
}
