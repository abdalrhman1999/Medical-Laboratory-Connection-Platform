<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'phone_number' => 'required|numeric|unique:users',
            'image' => 'image|mimes:jpeg,png,jpg',
        ]);
    }


    public function Register(Request $request)
    {
        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }

        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->phone_number = $request['phone_number'];

        if ($request->hasFile('image')) {

            // Get filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();

            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;

            // Uplaod image
            $path = $request->file('image')->storeAs('public/user_images/', $filenameToStore);

            $user->image = $filenameToStore;

        }
        $user->role_id = 5;
        $user->point = 0;

        $user->save();

        return response()->json(['data' => $user], 200);
    }

    public function addAdmin(Request $request)
    {
        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');

        if ($request->hasFile('image')) {

            // Get filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();

            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;

            // Uplaod image
            $path = $request->file('image')->storeAs('public/user_images/', $filenameToStore);

            $user->image = $filenameToStore;

        }
        $user->role_id = 1;

        $user->save();

        return response()->json(['data' => $user], 200);
    }


    public function addEmployee(Request $request)
    {
        if (Auth::user()->role_id !=1 ){
            return response()->json(['message'=>'Access Denied, You Can Not Add Employee'], 403);
        }

        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');


        if ($request->hasFile('image')) {

            // Get filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();

// Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;

            // Uplaod image
            $path = $request->file('image')->storeAs('public/user_images/', $filenameToStore);

            $user->image = $filenameToStore;

        }
        $user->role_id = 2;

        $user->save();

        return response()->json(['data' => $user], 200);
    }

    public function addLaboratory(Request $request)
    {
        if (Auth::user()->role_id !=1 &&  Auth::user()->role_id !=2){
            return response()->json(['message'=>'Access Denied, You Can Not Add Laboratory'], 403);
        }

        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');
        $user->laboratory_id = $request['laboratory_id'];

        if ($request->hasFile('image')) {

            // Get filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();

            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;

            // Uplaod image
            $path = $request->file('image')->storeAs('public/user_images/', $filenameToStore);

            $user->image = $filenameToStore;

        }
        $user->role_id = 3;

        $user->save();

        return response()->json(['data' => $user], 200);
    }


    public function addNurse(Request $request)
    {
        if (Auth::user()->role_id !=3  ){
            return response()->json(['message'=>'Access Denied, You Can Not Add Nurse'], 403);
        }

        $validatedData = $this->validator($request->all());
        if ($validatedData->fails())  {
            return response()->json(['errors'=>$validatedData->errors()], 400);
        }
        $current_user = Auth::user();
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->phone_number = $request->input('phone_number');
        $user->laboratory_id = $request['laboratory_id']??$current_user->laboratory_id;//??1

        if ($request->hasFile('image')) {

            // Get filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();

            // Get just the filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            // Create new filename
            $filenameToStore = $filename.'_'.time().'.'.$extension;

            // Uplaod image
            $path = $request->file('image')->storeAs('public/user_images/', $filenameToStore);

            $user->image = $filenameToStore;

        }
        $user->role_id = 4;

        $user->save();

        return response()->json(['data' => $user], 200);
    }

    public function Login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            // محاولة المصادقة
            $user = User::where('email', $request['email'])->firstOrFail();

        } else {
            return response()->json([
                'message' => 'بيانات تسجيل الدخول غير صحيحة'
            ], 401);
        }

$token = $user->createToken('User Password')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role_id,
            'userid' => $user->id
        ], 200);
    }
}
