@extends('layouts.index')

@section('header')
	@parent
@endsection

@section('menu')
	@parent
@endsection

@section('content')

<a href="{{url('/books')}}" class="btn btn-secondary">Назад</a>

<div class="row">
	<div class="col-5">
		<form action="{{ url('/delete/'.$book->id ) }} "  method="POST" onsubmit="return checkDel()">
			@csrf
            
			<button type="submit">Удалить</button>
		</form>
	</div>
	
</div>

<div class="card mr-2 mt-4">
  <div class="img_wrap">
    <img class="card-img-top" src="{{$book->image}}" alt="Обложка книги">
  </div>
  <div class="card-body">
    <h3 class="card-title">{{$book->name}}</h3>
	<p>Автор: {{$book->author}}</p>
	<p>Год: {{$book->year}}</p>
	<a href="#" class="btn btn-primary">Детальнее</a>
	<a href="#" class="btn btn-primary">Редактировать</a>
  </div>
</div>

<div class="col-5 mt-3">
	<h4>Редактировать книгу</h4>
	<form action="{{url('/editBook/'.$book->id)}}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
		  <label for="name" class="w-25">Название</label>
		  <input type="text" name="name" class="form-control" value="{{$book->name}}">
		</div>
		<div class="form-group">
		  <label for="author" class="w-25">Автор</label>
		  <input type="text" name="author" class="form-control" value="{{$book->author}}">
		</div>
		<div class="form-group">
		  <label for="year" class="w-25">Год</label>
		  <input type="text" name="year" class="form-control" value="{{$book->year}}">
		</div>
		<div class="form-group">
		  <label for="description" class="w-25">Описание</label>
		  <textarea name="description" id=""  cols="5" rows="5" class="form-control">{{$book->description}}</textarea>
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
		<input type="submit" value="Сохранить" class="btn btn-success">
	</form>
    @if(session('message'))
    <div class="alert alert-success">
      <h3>{{session('message')}}</h3>
    </div>
    @endif
</div>
<script type="text/javascript">
	
	function checkDel(){
		return confirm('вы действительно хотите удалить запись?');
	}
</script>

@endsection