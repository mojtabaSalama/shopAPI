<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\product_category;
use App\Models\admin_role;
use App\Models\admin_country;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

public function add_admin (Request $request ){

    $field=$request->validate ([
        'name' => ['required', 'string', 'max:255','unique:admins'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'role_id' => ['required', 'integer',],
        'country_id' => ['required', 'integer',],
        
    ]);
    $admin_role= admin_role::where('role_id',$field['role_id'])->first();
    $admin_country= admin_country::where('country_id',$field['country_id'])->first();
        
    if ($admin_role==null||$admin_country==null) {
        return response(['message'=>'wrong data'],401);       
    }
else {
    

   $admin= admin::create( [
        'name' => $field['name'],
        'role_id' => $field['role_id'],
        'country_id' => $field['country_id'],

        'password' =>Hash::make($field['password']),
        
    ]);
    config(['auth.guard.api.provider'=>'admin']); 
    
    
   
   
    return response([

        'admin name'=> $admin->name,
        'admin role'=> $admin_role->role,
        'admin country'=>$admin_country->country,
        'token'=>$admin->createToken('secrete')->plainTextToken
    ],200);
}
} 
public function logout(Request $request){
auth()->admin()->tokens()->delete();
return[
    'message'=>'You logged out'
];


}
public function login (Request $request ){

    $field=$request->validate ([
        
        'name' => ['required', 'string', 'max:255', ],
        'password' => ['required', 'string', 'min:6', ],
    ]);
   $admin=admin::where('name',$field['name'])->first();
   if(!$admin|| !Hash::check($field['password'],$admin->password)){
    return response(['message'=>'wrong data'],401);
   }
  config(['auth.guard.api.provider'=>'admin']);
   $token = $admin->createToken('secrete')->plainTextToken;

    return response([

        'admin'=> $admin,
        'token'=>$token   ],200);
} 



public function add_category (Request $request ){

    $field=$request->validate ([
        'category' => ['required', 'string', 'max:255','unique:product_categories'],
        
    ]);
    $category=new product_category;
    $category->category = $request->input('category');
   
    $category->save();
    


    return response([

        'categories'=> $category,
    ],200);
}



public function add_role (Request $request ){

    $field=$request->validate ([
        'role' => ['required', 'string', 'max:255','unique:admin_roles'],
        
    ]);
    $role=new admin_role;
    $role->role = $request->input('role');
   
    $role->save();
    


    return response([

        'role'=> $role,
    ],200);
}



public function add_country (Request $request ){

    $field=$request->validate ([
        'country' => ['required', 'string', 'max:255','unique:admin_countries'],
        
    ]);
    $country=new admin_country;
    $country->country = $request->input('country');
   
    $country->save();
    


    return response([

        'country'=> $country
    ],200);
}

public function all_admin()
{

    $admins= admin::orderby('name','desc')->get();
 
    
foreach ($admins as $admin ) { 
    $admin_role= admin_role::where('role_id',$admin->role_id)->first();
    $admin_country= admin_country::where('country_id',$admin->country_id)->first();
    return response([
        'admin'=> $admin,
        'admin role'=> $admin_role->role,
        'admin country'=>$admin_country->country,
    ]);
        }
}


public function all_role ( ){

    $roles=admin_role::orderby('role','desc')->get();
    function admins ( ){
    foreach ($roles as $role) {
        $admins=admin::where('role_id',$role->role_id)->first();
        $admin_country= admin_country::where('country_id',$admin->country_id)->first();
        return response([
            'admin'=> $admin,
            'admin country'=>$admin_country->country,
        ]);    }}
    return response([

        'roles'=> $roles,
        'role admins'=>admins(),
        
       
    ],200);

    
}


public function all_country ( ){

    $country=admin_country::orderby('country','desc')->get();
    


    return response([

        'country'=> $country
    ],200);
}

}

