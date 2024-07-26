<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
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
    //Nurse

    public function updateNurse(Request $request, $id)
    {
        if (Auth::user()->role_id != 4) {
            return response()->json(['message'=>'Access Denied, You Can Not Update Nurse'], 403);
        }

        $nurse = User::where('role_id', '=', 4)->find($id);

         // التحقق من وجود الممرض
        if (!$nurse) {
        return response()->json(['message' => 'Nurse not found'], 404);
    }

        if($request['name'])
            $nurse->name = $request['name'];

        if($request['email'])
            $nurse->email = $request['email'];

        if($request['password'])
            $nurse->password = $request['password'];


        if($request['phone_number'])
            $nurse->phone_number = $request['phone_number'];

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

            $nurse->image = $filenameToStore;

        }

        $nurse->save();

        return response()->json(['data' => $nurse], 200);
    }

    public function deleteNurse($id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message'=>'Access Denied, You Can Not Delete Nurse'], 403);
        }
        User::find($id)->delete();
        return response()->json(['message' => "Nurse Deleted"], 200);
    }
//
    public function getNurses() {
        $user = Auth::user();
        if (Auth::user()->role_id != 3) {
            return response()->json(['message'=>'Access Denied, You Can Not Get Nurse'], 403);
        }
        $nurses = User::where([['role_id',4],['laboratory_id',$user->laboratory_id]])->orderBy('created_at', 'desc')->get();
        return response()->json($nurses, 200);
    }

    public function getNurse($laboratory_id)
    {
        if (Auth::user()->role_id != 3) {
            return response()->json(['message'=>'Access Denied, You Can Not Get Nurse'], 403);
        }

        $nurse = User::where([['role_id', '=', 4], ['laboratory_id', '=', $laboratory_id]])->orderBy('created_at', 'desc')->get();

        return response()->json($nurse, 200);
    }
    
    public function searchNurse(Request $request)
    {
        $nurse = User::where('role_id', 4)
                       ->where('name', 'LIKE', '%' . $request['query'] . '%')
                        ->orderBy('created_at', 'desc')->get();

        return response()->json($nurse, 200);
    }
   

    

   

    //Laboratory

    public function updateLaboratory(Request $request, $id)
    {
        if (Auth::user()->role_id !=1 &&  Auth::user()->role_id !=2) {
            return response()->json(['message'=>'Access Denied, You Can Not Update Laboratory'], 403);
        }

        $laboratory = User::where('role_id', '=', 3)->find($id);

         // التحقق من مخبري
        if (!$laboratory) {
            return response()->json(['message' => 'Laboratory not found'], 404);
        }

if($request['name'])
            $laboratory->name = $request['name'];

        if($request['email'])
            $laboratory->email = $request['email'];

        if($request['password'])
            $laboratory->password = $request['password'];


        if($request['phone_number'])
            $laboratory->phone_number = $request['phone_number'];

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

            $laboratory->image = $filenameToStore;

        }

        $laboratory->save();

        return response()->json(['data' => $laboratory], 200);
    }

    public function deleteLaboratory($id)
    {
        if (Auth::user()->role_id !=1 &&  Auth::user()->role_id !=2) {
            return response()->json(['message'=>'Access Denied, You Can Not Delete Laboratory'], 403);
        }

        $laboratory = User::where('role_id', '=', 3)->find($id);

        $laboratory->delete();
        return response()->json(['message' => "Laboratory Deleted"], 200);
    }

    public function getLaboratory()
    {
        if (Auth::user()->role_id !=1 &&  Auth::user()->role_id !=2) {
            return response()->json(['message'=>'Access Denied, You Can Not Get laboratory'], 403);
        }

        $laboratory = User::where('role_id', 3)->orderBy('created_at', 'desc')->get();

        return response()->json($laboratory, 200);
    }
    public function searchLaboratory(Request $request)
    {
        $laboratory = User::where('role_id', 3)
                             ->where('name', 'LIKE', '%' . $request['query'] . '%')
                             ->orderBy('created_at', 'desc')->get();

        return response()->json($laboratory, 200);
    }

     //Employee

     public function updateEmployee(Request $request, $id)
     {
         if (Auth::user()->role_id !=1 ) {
             return response()->json(['message'=>'Access Denied, You Can Not Update Employee'], 403);
         }

         $employee = User::where('role_id', '=', 2)->find($id);

          // التحقق من الموظف
         if (!$employee) {
         return response()->json(['message' => 'employee not found'], 404);
     }

         if($request['name'])
             $employee->name = $request['name'];

         if($request['email'])
             $employee->email = $request['email'];

         if($request['password'])
             $employee->password = $request['password'];


         if($request['phone_number'])
             $employee->phone_number = $request['phone_number'];

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

          $employee->image = $filenameToStore;

    }

         $employee->save();

         return response()->json(['data' => $employee], 200);
     }

     public function deleteEmployee($id)
     {
         if (Auth::user()->role_id !=1 ) {
             return response()->json(['message'=>'Access Denied, You Can Not Delete Employee'], 403);
         }

         $employee = User::where('role_id', '=', 2)->find($id);

$employee->delete();
         return response()->json(['message' => "Employee Deleted"], 200);
     }

     public function getEmployee()
     {
         if (Auth::user()->role_id !=1 ) {
             return response()->json(['message'=>'Access Denied, You Can Not Get Employee'], 403);
         }

         $employee = User::where('role_id', 2)->orderBy('created_at', 'desc')->get();

         return response()->json($employee, 200);
     }

     public function searchEmployee(Request $request)
    {
        $employee = User::where('role_id', 2)
                          ->where('name', 'LIKE', '%' . $request['query'] . '%')
                        ->orderBy('created_at', 'desc')->get();

        return response()->json($employee, 200);
    }
 //User

 public function updateUser(Request $request)
{
    if (Auth::user()->role_id != 5) {
        return response()->json(['message' => 'Access Denied, You Can Not Update User'], 403);
    }

    // البحث عن المستخدم
    $user = User::where('role_id', 5)->find($request->id);

    // التحقق من وجود المستخدم
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // تحديث خصائص المستخدم
    if ($request->has('name')) {
        $user->name = $request->input('name');
    }

    if ($request->has('email')) {
        $user->email = $request->input('email');
    }

    if ($request->has('password')) {
        $user->password = bcrypt($request->input('password')); // تأكد من تشفير كلمة المرور
    }

    if ($request->has('phone_number')) {
        $user->phone_number = $request->input('phone_number');
    }

    // التعامل مع الصورة المرفقة
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

        // تعيين اسم الملف الجديد للمستخدم
        $user->image = $filenameToStore;
    }

    // حفظ التغييرات
    $user->save();

    return response()->json(['data' => $user], 200);
}


 public function getUser($userid)
 {
    //  if (Auth::user()->role_id !=1 && Auth::user()->role_id !=2 ) {
    //      return response()->json(['message'=>'Access Denied, You Can Not Get User'], 403);
    //  }

    //  $user = User::where('role_id', 5)->orderBy('created_at', 'desc')->get();
    $user =User::where('id',$userid)->first();

     return response()->json($user, 200);
 }

 public function searchUser(Request $request)
{
    $user = User::where('role_id', 5)
                      ->where('name', 'LIKE', '%' . $request['query'] . '%')
                    ->orderBy('created_at', 'desc')->get();

    return response()->json($user, 200);
}


}
