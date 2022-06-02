@extends('layouts.index')

@section('header')
	@parent
@endsection

@section('menu')
	@parent
@endsection

@section('content')

  <div class="row mb-3">
    <div class="col-3">
      <div>Поиск</div>
      <form action="{{ url('/search')}}" method="POST">
        @csrf
        <input type="text" name="search">
        <input type="submit" value="Поиск">
      </form>
    </div>
    <div class="col-5">
      <div>Сортировка по автору и названию</div>
      <form action="{{url('/sort')}}" method="POST" onsubmit="return checkVal()">
        @csrf
        <select name="sort" id="selected">
          <option value=" ">Выбрать</option>
          <option value="sortByName" 
          @if(isset($selected))
          @if('sortByName' == $selected)
          selected="selected"
          @endif
          @endif>По названию</option>
          @if(isset($books))
          @foreach($books as $book)
          <option value="{{$book->id}}"
          @if(isset($selected))
          @if($book->id == $selected)
          selected="selected"
          @endif
          @endif>{{$book->author}}</option>
          @endforeach
          @endif
        </select>
        <input type="submit" value="Выбрать">
      </form>
    </div>
  </div>
  @auth
  <div class="row">
  	<h3>Добавить Книгу</h3>
  </div>
  <div class="row">
    @if(count($errors) > 0)
    <div class="w-100">
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p><strong>Что-то пошло не так!</strong></p>
        <p>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </p>
      </div>
    </div>
    @endif
    <div  class="col-10" id="bookForm"></div>
  	<form action="{{url('/addBook')}}" method="POST" enctype="multipart/form-data"  onsubmit="return checkBook()">
  		@csrf
  		<div class="form-group">
  		  <label for="name" class="w-25">Название</label>
  		  <input type="text" name="name" class="form-control" id="name" required onchange="checkInput(event)">
  		</div>
  		<div class="form-group">
  		  <label for="author" class="w-25">Автор</label>
  		  <input type="text" name="author" class="form-control" id="author" required onchange="checkInput(event)">
  		</div>
  		<div class="form-group">
  		  <label for="year" class="w-25">Год</label>
  		  <input type="text" name="year" class="form-control" id="year" onchange="checkInput(event)">
  		</div>
  		<div class="form-group">
  		  <label for="description" class="w-25">Описание</label>
  		  <textarea name="description"   cols="5" rows="5" class="form-control" id="text" required onchange="checkInput(event)"></textarea>
  		</div>
      <div class="form-group">
        <label for="category">Добавить категорию</label>
        <select name="category" id="">
          @if(isset($cat))
          @foreach($cat as $c)
          <option value="{{$c->id}}">{{$c->name}}</option>
          @endforeach
          @endif
        </select>
      </div>
  		<div class="form-group">
  		  <label for="image">Добавить изображение</label>
  		  <input type="file" name="image" class="form-control-file" id="image" accept="image/jpeg, image/jpg, image/png">
  		</div>
  		<input type="submit" value="Добавить" class="btn btn-success"  id="btn" disabled="true">
      
  	</form>
    @endauth
    @if(session('message'))
    <div class="col-10">
      <div class="alert alert-success">
        <h3>{{session('message')}}</h3>
      </div>
    </div>
    @endif
  </div>


  <div class="row mt-4">
    @if(isset($results))
      @foreach($results as $result)
      <div class="card mr-2 mt-4">
        <div class="img_wrap">
          <img class="card-img-top" src="{{$result->image}}" alt="Обложка книги">
        </div>
        <div class="card-body">
        <h3 class="card-title">{{$result->name}}</h3>
        <p>Автор: {{$result->author}}</p>
        <p>Год: {{$result->year}}</p>
        <a href="#" class="btn btn-primary">Детальнее</a>
       

         @if (!Auth::guest())
          @if (!Auth::guest() && $result->user_id == Auth::user()->id)
             <a href="{{url('/edit/'.$result-id)}}" class="btn btn-primary">Редактировать</a>   
          @endif
        @endif
        </div>
      </div>
      @endforeach

    
      @elseif(isset($books) && !isset($result) && !isset($byName))
      @foreach($books as $book)

      <div class="card mr-2 mt-4">
        <div class="img_wrap">
    	    <img class="card-img-top" src="{{$book->image}}" alt="Обложка книги">
        </div>
    	  <div class="card-body">
    		<h3 class="card-title">{{$book->name}}</h3>
    		<p>Автор: {{$book->author}}</p>
        <p>Год: {{$book->year}}</p>
    		<a href="#" class="btn btn-primary">Детальнее</a>

         @if (!Auth::guest())
          @if (!Auth::guest() && $book->user_id == Auth::user()->id)
             <a href="{{url('/edit/'.$book->id)}}" class="btn btn-primary">Редактировать</a>   
          @endif
        @endif
     
    	  </div>
  	  </div>
      @endforeach
    

     @elseif(isset($byName) && !isset($result) && isset($books))
     
        @foreach($byName as $name)

        <div class="card mr-2 mt-4">
          <div class="img_wrap">
            <img class="card-img-top" src="{{$name->image}}" alt="Обложка книги">
          </div>
          <div class="card-body">
          <h3 class="card-title">{{$name->name}}</h3>
          <p>Автор: {{$name->author}}</p>
          <p>Год: {{$name->year}}</p>
          <a href="#" class="btn btn-primary">Детальнее</a>

           @if (!Auth::guest())
             @if (!Auth::guest() && $name->user_id == Auth::user()->id)
              <a href="{{url('/edit/'.$name->id)}}" class="btn btn-primary">Редактировать</a>   
             @endif
           @endif
          
          </div>
        </div>
        @endforeach
     @endif

   </div>
   <div class="d-felx justify-content-center m-3">

            

  </div>
   <script type="text/javascript">
     

  function checkBook(e) {
 
    errorDiv = document.getElementById('bookForm');
    btn = document.getElementById('btn');
    name = document.getElementById('name').value;
    author = document.getElementById('author').value;
    year = document.getElementById('year').value;
    textarea = document.getElementById('text').value;
    
 
    if(name.length <= 150) {
      if(author.length <= 100) {
        if(year.length == 4) {
          if(textarea.length <= 2000) {
           return true;
          }
          else {
        errorDiv.innerHTML = 'Поле описание заполнено не корректно';
      return false;
      }
        }
        else {
        errorDiv.innerHTML = 'Поле год заполнено не корректно';
      return false;
      }
      }
      else {
        errorDiv.innerHTML = 'Поле автор заполнено не корректно';
      return false;
      }
    }
    else {
      errorDiv.innerHTML = 'Поле имя заполнено не корректно';
      btn.disabled= true;
      return false;
    }

  }

  function checkInput(e) {
    name = document.getElementById('name').value.length;
    author = document.getElementById('author').value.length;
    year = document.getElementById('year').value.length;
    textarea = document.getElementById('text').value.length;
    btn = document.getElementById('btn');
   

    if(name > 1 && author > 1 && year == 4 && textarea > 10) btn.disabled = false;
  }
  </script>



@endsection

<script>
  function checkVal() {
    $select = document.getElementById('selected');
    if($select.value === " ") return false;
  }

 
</script>
