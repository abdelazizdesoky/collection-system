<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AreaController extends Controller
{
    public function index(): View
    {
        $areas = Area::latest()->paginate(15);
        return view('areas.index', compact('areas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|unique:areas,name|max:255']);
        Area::create($request->only('name'));
        return redirect()->route('areas.index')->with('success', 'Area created successfully.');
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255|unique:areas,name,' . $area->id]);
        $area->update($request->only('name'));
        return redirect()->route('areas.index')->with('success', 'Area updated successfully.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        if ($area->customers()->exists()) {
            return redirect()->route('areas.index')->with('error', 'Cannot delete area assigned to customers.');
        }
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }
}
