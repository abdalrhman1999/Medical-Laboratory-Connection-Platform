<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function getRole()
    {
        $Roles = Role::all();
        return response()->json($Roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:15',
        ]);

        $Role = new Role;

        $Role->name = $request['name'];

        $Role->save();

        return response()->json($Role, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateRole(Request $request, $id)
    {
        $Role = Role::find($id);

        if ($request['name'])
            $Role->name = $request['name'];

        $Role->save();

        return response()->json($Role, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteRole($id)
    {
        $Role = Role::find($id);

        $Role->delete();

        return response()->json(['Deleted Successful'], 200);
    }
}
