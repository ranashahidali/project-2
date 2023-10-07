<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
// use Spatie\Backtrace\File;
use Illuminate\Support\Facades\File;
use Image;  

class CategoryController extends Controller
{
    public function index(Request $request){
        
        $categories = Category::latest();

        if(!empty($request->get('keyword'))){
            $categories=$categories->where('name','like','%'.$request->get('keyword').'%');

        }
        
        $categories = $categories->paginate(10);

        return view('admin.layouts.category.list',compact('categories'));

    }


    public function create(){

        // echo "Wellcome categories page";
        return view('admin.layouts.category.create');
        
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()){

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();


            if(!empty($request->image_id)){
                $tempImage=TempImage::find($request->image_id);
                $extArray=explode('.',$tempImage->name);
                $ext=last($extArray);

                $newImageName=$category->id.'.'.$ext;
                $sPath=public_path().'/temp'.$tempImage->name;
                $dPath=public_path().'/uploads/category'.$newImageName;
                File::class($sPath,$dPath);

                $dPath=public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });

                $img->save($dPath);

                $category->status = $newImageName;
                $category->save();
            }

            $request->session()->flash('success','Category added successfully');

            return response()->json([
                'status'=>true,
                'message'=>'Category added successfully'

            ]);

        }else{

            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()

            ]);
        }
        
    }

    public function edit($categoryId,Request $request){
        $category = Category::find($categoryId);

        if(empty($category)){
            return redirect()->route('categories.index');
        }

        return view('admin.layouts.category.edit',compact('category'));

        
    }

    public function update($categoryId,Request $request){

        $category = Category::find($categoryId);

        if(empty($category)){
            $request->session()->flash('error','Category Not Found'); 
            return response()->json([

                'status'=>false,
                'notFound'=>true,
                'message'=> 'category not found'

            ]);
        }


        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique: table,categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()){

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;


            if(!empty($request->image_id)){
                $tempImage=TempImage::find($request->image_id);
                $extArray=explode('.',$tempImage->name);
                $ext=last($extArray);

                $newImageName=$category->id.'-'.time().'.'.$ext;
                $sPath=public_path().'/temp'.$tempImage->name;
                $dPath=public_path().'/uploads/category'.$newImageName;
                File::class($sPath,$dPath);

                $dPath=public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);

                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });

                $img->save($dPath);

                $category->status = $newImageName;
                $category->save();

                //  delete old image
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
            }

            $request->session()->flash('success','Category updated successfully'); 

            return response()->json([
                'status'=>true,
                'message'=>'Category updated successfully'

            ]);

        }else{

            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()

            ]);
        }
        
    
        

    }

    public function destory($categoryId, Request $request){
        $category=Category::find($categoryId);
        if(empty($category)){
            $request->session()->flash('error','Category Not Found');
            // return redirect()->route('categories.index');
            return response()->json([
                'status'=>true,
                'message'=>'Category not Found'
    
            ]);
        }

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);

        $category->delete();

        $request->session()->flash('success','Category deleted successfully');

        return response()->json([
            'status'=>true,
            'message'=>'Category deleted successfully'

        ]);

        
    }

    
}

