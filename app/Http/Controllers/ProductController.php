<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        //get all data from data base
        $data = Product::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        //post data to database
        $this->validate($request,[
            'title'=>'required',
            'price'=>'required',
            'image'=>'required',
            'description'=>'required'
        ]);
        $product = new Product();
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->image = time().$request->input('image');
        if($request->hasFile('image')){// dd("sd");
            $file = $request->input('image');
            $allowFileExtension = ['pdf','png','jpg'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension , $allowFileExtension);
            if($check){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
               // dd($name);
                $product->image = $name;
            }
        }
        $product->save();
        return response()->json($product);


    }

    public function show($id)
    {
        //give one item from table
        $data = Product::find($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        //update item
        $this->validate($request,[
            'title'=>'required',
            'price'=>'required',
            'image'=>'required',
            'description'=>'required'
        ]);
        $product = Product::find($id);
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        if($request->hasFile('image')){
            $file = $request->input('image');
            $allowFileExtension = ['pdf','png','jpg'];
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension , $allowFileExtension);
            if($check){
                $name = time() . $file->getClientOriginalName();
                $file->move('images',$name);
                $product->image = $name;
            }
        }
        $product->save();
        return response()->json($product);
    }

    public function destroy($id)
    {
        //delete data
        $data = Product::find($id);
        $data->delete();
        return response()->json('Product deleted successfully');
    }
}
