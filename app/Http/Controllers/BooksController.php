<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Books;
use App\Models\Categories;
use DB;
use Validator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $books = Books::all();
        //$books = Books::orderBy('created_at', 'desc')->Paginate(3);
        $cat = Categories::all();
        
        return view('books.books', ['books'=>$books, 'cat'=>$cat]);
    
    }

    public function editIndex($id)
    {
        $bId = trim(htmlspecialchars($id));
        $book = Books::find($bId);
        $cat = Categories::all();
        
        return view('books.edit', ['book'=>$book, 'cat'=>$cat]);
    
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
         $validator = Validator::make($request->all(), [ 
                            'name' => 'required|max:150',
                            'author' => 'required|max:100',
                            'year' => 'max:4',
                            'description' => 'required|max:2000',
                            'image' => 'image|max:500000',
                        ]);


        if($validator->fails()) {
            return redirect('/books')->withInput()->withErrors($validator);
        }
        
        $filename = "";
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $store_filename = $file->getClientOriginalName();

            $file->move(public_path().'/images/covers/', $store_filename);

            $filename = '/images/covers/'.$store_filename;
        } else {
            $filename = "";
        }

        /*$book = new Books;
        $book->user();
        $book->name = $request->name;
        $book->author = $request->author;
        $book->year = $request->year;
        $book->description = $request->description;*/

      


        $book = $request->user()->books()->create([
            'name' => $request->name,
            'author' => $request->author,
            'year' => $request->year,
            'description' => $request->description,
            'categories_id' => $request->category,
            'image'=>$filename
        ]);

        return redirect()->action([BooksController::class, 'index'])->with('message', 'Книга '.$book->name.' была добавлена с id='.$book->id);

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
    public function edit(Request $request, Books $id)
    {
        //
        $img = $request->file('image');

        if($img != null) 
        {   
            $book_cover = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path().'/images/covers/',$book_cover);
            $id->image='/images/covers/'.$book_cover;
        
        } else {
            $id->image=$id->image;
        }

        $id->name = $request->name;
        $id->author = $request->author;
        $id->year = $request->year;
        $id->description = $request->description;
        $id->categories_id = $request->category;
        $id->save();

        return redirect()->action([BooksController::class, 'editIndex'], $id)->with('message', 'Книга '.$id->name.' была обновлена с id='.$id->id);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Books $id)
    {
        if( $request->user()->books()->find($id) ){
            $id->delete();
        }

        return redirect()->action([BooksController::class, 'index'])->with('message', 'Книга '.$id->name.' была удалена успешно');

    }

    public function search(Request $request)
    {   
        $search = $request->search;
        $results = DB::table('books')
                    ->where('name', 'like', "%".$search."%")
                    ->orWhere('author', 'like', "%".$search."%")
                    ->orWhere('description', 'like', "%".$search."%")
                    ->get();
        $books = Books::all();
        
       

        //return view('books.books', ['results'=>$results]);

       // return redirect()->route('/books/{id}', ['results'=>$results]);
        return view('books.books', ['results'=>$results, 'books'=>$books]);

    }

    public function sort(Request $request) 
    {   
        //$books = Books::paginate(3); //без пагинации all()
        $books = Books::all();
        if($request->sort == 'sortByName')
        {   
            $selected = $request->sort;
            $byName = Books::orderBy('name')->get();
            return view('books.books', ['byName'=>$byName, 'books'=>$books, 'selected'=>$selected]);
        } else {
            $selected = $request->sort;
            $author = Books::find($request->sort);
            $byName = DB::table('books')
                     ->where('author', '=', $author->author)
                     ->get();
                     return view('books.books', ['byName'=>$byName, 'books'=>$books, 'selected'=>$selected]);
        }
    }


}
