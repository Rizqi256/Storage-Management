<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->get('search');
    $files = File::query();

    if ($search) {
        $files->where('name', 'LIKE', '%' . $search . '%')
              ->orWhere('description', 'LIKE', '%' . $search . '%');
    }

    $files = $files->paginate(3)->appends(['search' => $search]);

    return view('files.index', compact('files'));
}

public function create()
{
    return view('files.create');
}

//public function store(Request $request)
//{
   //$file = new File();
   //$file->name = $request->input('name');
    //$file->quantity = $request->input('quantity');
   // $file->description = $request->input('description');

    //if ($request->hasFile('photo')) {
        //$file->photo = $request->file('photo')->store('public/photos');
    //}

    //$file->save();

    //return redirect()->route('files.index');
//}

public function edit($id)
{
    $file = File::findOrFail($id);
    return view('files.edit', compact('file'));
}

public function update(Request $request, $id)
{
    $file = File::findOrFail($id);
    $file->name = $request->input('name');
    $file->quantity = $request->input('quantity');
    $file->description = $request->input('description');

    if ($request->hasFile('photo')) {
        $file->photo = $request->file('photo')->store('public/photos');
    }

    $file->save();

    return redirect()->route('files.index');
}

public function destroy($id)
{
    $file = File::findOrFail($id);
    $file->delete();

    return redirect()->route('files.index');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'quantity' => 'required|integer',
        'photo' => 'nullable|image|max:2048',
    ]);

    $file = new File([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'quantity' => $request->get('quantity'),
        'user_id' => Auth::id(),
    ]);

    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $filename = time() . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs('public/photos', $filename);
        $file->photo = $path;
    }

    $file->save();

    return redirect()->route('files.index')->with('success', 'File added successfully');
}

}
