<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Usuarios', [
            'users' => User::select('id', 'name', 'email', 'cuatrimestre', 'role')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'role' => ['required','in:admin,alumno'],
            'cuatrimestre' => ['nullable','integer','between:1,11'],
        ]);

        // Si es alumno, cuatrimestre obligatorio
        if ($data['role'] === 'alumno') {
            $request->validate([
                'cuatrimestre' => ['required','integer','between:1,11'],
            ]);
            $data['cuatrimestre'] = (int) $request->cuatrimestre;
        } else {
            $data['cuatrimestre'] = null;
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email,' . $user->id],
            'password' => ['nullable','string','min:8','confirmed'],
            'role' => ['required','in:admin,alumno'],
            'cuatrimestre' => ['nullable','integer','between:1,11'],
        ]);

        // Si es alumno, cuatrimestre obligatorio
        if ($data['role'] === 'alumno') {
            $request->validate([
                'cuatrimestre' => ['required','integer','between:1,11'],
            ]);
            $data['cuatrimestre'] = (int) $request->cuatrimestre;
        } else {
            $data['cuatrimestre'] = null;
        }

        // Password solo si viene
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
