<?php

namespace App\Http\Controllers;

use App\Models\VisitType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VisitTypeController extends Controller
{
    public function index(): View
    {
        $types = VisitType::latest()->paginate(15);
        return view('visit-types.index', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'name' => 'required|string|alpha_dash|unique:visit_types,name|max:255',
        ]);
        
        VisitType::create(['name' => $request->name, 'label' => $request->label]);
        return redirect()->route('visit-types.index')->with('success', 'Visit Type created successfully.');
    }

    public function update(Request $request, VisitType $visitType): RedirectResponse
    {
        if ($visitType->is_system) {
            return redirect()->back()->with('error', 'Cannot edit system types.');
        }

        $request->validate([
            'label' => 'required|string|max:255',
            // Name usually shouldn't change for logic mapping, but for custom types it's fine
            'name' => 'required|string|alpha_dash|max:255|unique:visit_types,name,' . $visitType->id,
        ]);

        $visitType->update($request->only(['name', 'label']));
        return redirect()->route('visit-types.index')->with('success', 'Visit Type updated successfully.');
    }

    public function destroy(VisitType $visitType): RedirectResponse
    {
        if ($visitType->is_system) {
            return redirect()->back()->with('error', 'Cannot delete system types.');
        }
        $visitType->delete();
        return redirect()->route('visit-types.index')->with('success', 'Visit Type deleted successfully.');
    }
}
