<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AdminAuthenticate;
use App\Models\Brand;
use Illuminate\Http\Request;
// use Illuminate\Contracts\Http\Kernel;
// use Illuminate\Http\Request;


// use trim;
// use Illuminate\Support\Facades\Auth;
// use App\Http\Middleware\RedirectIfAuthenticated;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




                       
Route::group(['prefix' => 'admin'],function(){

    Route::group(['middleware' => 'admin.guest'],function(){

        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');

        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');

        // Route::post(['get', 'post','/authenticate'], [AdminLoginController::class,'authenticate'])->name('admin.authenticate');

    });

    
    // Route::middleware(['admin.auth'])->group(function()

    

    Route::group(['middleware' => 'admin.auth'],function(){
        

        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');


        // Category Routes

        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');

        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class,'destory'])->name('categories.delete');

        // sub category route

        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');

        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{sub-category}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{sub-category}',[SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{sub-category}',[SubCategoryController::class,'destory'])->name('sub-categories.delete');


        // Brands Routes

        Route::get('/brands',[BrandController::class,'index'])->name('brands.index');
        Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');

        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::post('/brands',[BrandController::class,'store'])->name('brands.store');

        Route::put('/brands/{brand}',[BrandController::class,'update'])->name('brand.update');


        // temp image create
        Route::post('/upload-tem-image',[TempImagesController::class,'create'])->name('temp-images.create');
       
        Route::get('getSlug',function(Request $request){

            $slug='';
            if (!empty($request->title)){
                $slug = Str::slug($request->title);
            }

            return response()->json([

                'status' => true,
                'slug' => $slug

            ])->name('getSlug');


        });

    });

    // Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
    // Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
    // Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
   
    // Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
    // Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');



});
