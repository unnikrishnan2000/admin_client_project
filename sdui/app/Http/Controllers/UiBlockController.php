<?php

namespace App\Http\Controllers;

use App\Models\UiBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UiBlockController extends Controller
{
    public function welcome()
    {
        return Inertia::render('Welcome', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'laravelVersion' => app()->version(),
            'phpVersion' => phpversion(),
            'blocks' => UiBlock::orderBy('order')->get(),
        ]);
    }

    public function adminIndex()
    {
        return Inertia::render('Admin/UiBlocks', [
            'blocks' => UiBlock::orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:banner,card,list,stats',
            'status' => 'required|boolean',
            'config' => 'nullable|array',
        ]);

        $data['order'] = UiBlock::max('order') + 1;

        $block = UiBlock::create($data);

        if ($request->wantsJson()) {
            return response()->json(['block' => $block]);
        }

        return Redirect::route('admin.blocks.index');
    }

    public function update(Request $request, UiBlock $block)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:banner,card,list,stats',
            'status' => 'required|boolean',
            'config' => 'nullable|array',
            'order' => 'required|integer',
        ]);

        $block->update($data);

        if ($request->wantsJson()) {
            return response()->json(['block' => $block]);
        }

        return Redirect::route('admin.blocks.index');
    }

    public function destroy(UiBlock $block)
    {
        $block->delete();

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return Redirect::route('admin.blocks.index');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        foreach ($data['order'] as $idx => $id) {
            UiBlock::where('id', $id)->update(['order' => $idx + 1]);
        }

        return response()->json(['success' => true]);
    }
}
