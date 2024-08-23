<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\tb_admin;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = tb_admin::orderBy('id_admin', 'desc')->get();

        $token = $request->session()->get('token') ?? $request->input('token');

        return view('admin.page.management.user.index', [
            'menu_active' => 'user',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $token = $request->session()->get('token') ?? $request->input('token');

        return view('admin.page.management.user.create', [
            'menu_active' => 'user',
            'user' => tb_admin::all(),
            'token' => $token,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id, int $user)
    {
        $token = $request->session()->get('token') ?? $request->input('token');

        $userInput = $request->route('user');
        $user = tb_admin::findOrFail($userInput);

        return view('admin.page.management.user.create', [
            'menu_active' => 'user',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
