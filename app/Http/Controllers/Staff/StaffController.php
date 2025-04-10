<?php

namespace App\Http\Controllers\Staff;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get the string query param
        $search = $request->get('search');
        //search the data based on that string query param
        $users = User::where('name', 'ilike', '%'. $search . '%')->orderBy('id', 'desc')->get();
        //return response in json format with collection
        return response()->json([
            'users' => UserCollection::make($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //check if username already exists
        $is_user_exists = User::where('username', $request->username)->first();
        //if it is return with this response
        if($is_user_exists){
            return response()->json([
                'http_code' => 403,
                'message' => 'Username already exists'
            ]);
        }

        //check if there is any file request, name image
        if($request->hasFile('image')){
            //store it in folder users
            $path = Storage::putFile('users', $request->file('image'));
            //saved the path to the image
            $request->request->add(['avatar' => $path]);
        }

        //check if there is any password request, and encrypt the password
        if($request->password){
            $request->request->add(['password' => bcrypt($request->password)]);
        }

        //store all request
        $user = User::create($request->all());
        //find the role id based on request and assign it to the $user
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);

        //return with this response
        return response()->json([
            'http_code' => 200,
            'user' => UserResource::make($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if a different user already uses the requested username
        $is_user_exists = User::where('username', $request->username)->where('id', '<>', $id)->first();
        // If such a user exists, return a 403 error response
        if($is_user_exists){
            return response()->json([
                'http_code' => 403,
                'message' => 'Username already exists'
            ]);
        }

        // Find the user by ID or throw a 404 error if not found
        $user = User::findOrFail($id);

        // If an image file is uploaded...
        if($request->hasFile('image')){
            // Delete the old avatar if it exists
            if($user->avatar){
                Storage::delete($user->avatar);
            }

            // Store the new image and add its path to the request as 'avatar'
            $path = Storage::putFile('users', $request->file('image'));
            $request->request->add(['avatar' => $path]);
        }

        // If a new password is provided, hash it and add to the request
        if($request->password){
            $request->request->add(['password' => bcrypt($request->password)]);
        }

        // Update the user with all request data
        $user->update($request->all());

        // If the user's role is being changed...
        if($request->role_id && $request->role_id != $user->role_id){
            // Remove the old role
            $role_old = Role::findOrFail($user->role_id);
            $user->removeRole($role_old);

            // Assign the new role
            $role_new = Role::findOrFail($request->role_id);
            $user->assignRole($role_new);
        }

        // Return a success response with the updated user data
        return response()->json([
            'http_code' => 200,
            'user' => UserResource::make($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the user by ID or throw a 404 error if not found
        $user = User::findOrFail($id);
        // If there is 'avatar' image for the user, delete the file from storage
        if($user->avatar){
            Storage::delete($user->avatar);
        }
        // Delete the user
        $user->delete();

        // Return a success response with message
        response()->json([
            'http_code' => 200,
            'message' => 'User Deleted Successfully'
        ]);
    }
}
