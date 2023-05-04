<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function create_product (Request $request ){

        $this->validate($request,[

            'product_name'=>'required |max:255 |string',
            'product_category_id'=>'required |max:255 |integer',
            'product_image'=>'image|required|max:1999',
            'price'=>'integer|requireds',
            'amount'=>'integer|required'
            
            ]);
            //file uploade
            $filenamewithext=$request->file('product_image')->getClientOriginalName();
            $filename= pathinfo($filenamewithext,PATHINFO_FILENAME);
            $extention=$request->file('product_image')->getClientOriginalExtension();
            $filenametostore=$filename.'_'.time().'.'.$extention;
            $path=$request->file('product_image')->storeAs('public/products/product_image',$filenametostore);
          
          
          
            $product_category=product_category::where('category_id',$request->input('product_category_id'))->first();
            
            
            $product=new Product;
            $product->product_name = $request->input('product_name');
            $product->product_category = $product_category->category;
            $product->created_by=auth()->user()->id;
            $product->product_image=$filenametostore;
            $product->product_id = Str::random(10);
            $product->product_name = $request->input('product_name');
            $product->price = $request->input('price');
            $product->amount = $request->input('amount');
            $product->save();
            
            $admin=admin::find($product->created_by);
        
        return response([
    
            'product_name'=> $product->product_name,
            'product_id'=> $product->product_id,
            'product_category'=> $product_category->category,
            'price'=> $product->price,
            'amount'=> $product->amount,
            'created_by'=> $user->name,
        ],200);
    } 
    public function show_product ( $product_id )
    {
        $product =Product::where('product_id',$product_id )->first();

        return response([
    
            'product_name'=> $product->product_name,
            'product_category'=> $product->product_category,
            'product_image'=> $product->product_image,
            'product_id'=> $product->product_id,
            'price'=> $product->price,
            'amount'=> $product->amount,
            'created_by'=> $user->name,
        ],200);
     }    


     public function edit_product (Request $request , $product_id ){

        $this->validate($request,[

            'product_name'=>'required |max:255 |string',
            'product_category_id'=>'required |max:255 |string',
            'product_image'=>'image|required|max:1999',
            'price'=>'integer|requireds',
            'amount'=>'integer|required'        
            ]);
            //file uploade
            $filenamewithext=$request->file('product_image')->getClientOriginalName();
            $filename= pathinfo($filenamewithext,PATHINFO_FILENAME);
            $extention=$request->file('product_image')->getClientOriginalExtension();
            $filenametostore=$filename.'_'.time().'.'.$extention;
            $path=$request->file('product_image')->storeAs('public/products/product_image',$filenametostore);
            
            $product_category=product_category::where('category_id',$request->input('product_category_id'))->first();
            
            
            $product= Product::where('product_id',$product_id )->first() ;
            $product->product_name = $request->input('product_name');
            $product->product_category = $product_category->category;
            $product->created_by=auth()->user()->id;
            $product->product_image=$filenametostore;
            $product->price = $request->input('price');
            $product->amount = $request->input('amount');
            $product->save();
            
            
        
        return response([
    
            'product_name'=> $product->product_name,
            'product_category'=> $product->product_category,
            'product_image'=> $product->product_image,
            'product_id'=> $product->product_id,
            'price'=> $product->price,
            'amount'=> $product->amount,
            'created_by'=> $user->name,
        ],200);
    } 

    public function delete($product_id)
    {
        $product =Product::where('product_id',$product_id )->first();


            Storage::delete('public/products/'.$product->product_image);
        
        
    
        $product->delete();
        return[
            'message'=>'product is deleted successfully '
        ];
    }
    public function index()
    {

        $products= Product::orderby('created_at','desc')->get();
     
        return response([

            'products'=> $products,
               ],200);
    } 
     }   
        
    


    