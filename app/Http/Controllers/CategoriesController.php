<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Categories;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $all_categories = Categories::orderBy('created_at', 'asc')->get();
        $id = Auth::id();
        if($id === 1)
        {
            $admin = Auth::user();
        }
 
        return view('categories.categories', ['allCategories' => $all_categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        /*Validator::make($request->all(), [
            'name' => 'required|max:255',
            
        ])->validate();

        if ($validator->fails()) {
            return redirect('post/store')
                        ->withErrors($validator)
                        ->withInput();
        }*/

        $user = Auth::user();
        if($user->name == 'admin')
        {
        $category = new Categories;
        $category->name = $request->name ;
        $category->save();
        
        return $category;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $updCat = Categories::find($id);

        if($updCat) {
        $updCat->name = $request->upd_name;
        $updCat->save();
        }

        return redirect('/categories');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        
        $catId = Categories::find($request->category);
        $catId->delete();
        return response()->json(['message'=>'Категория '.$catId->name.' удалена'], 200);
    }
}
