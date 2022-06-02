@extends('layouts.index')

@section('header')
  @parent
@endsection

@section('menu')
	@parent
@endsection

@section('content')
<div class="container">
  @if (!Auth::guest())
  @if (!Auth::guest() && Auth::user()->name == 'admin')
  <div class="row">
    <div class="col-10">
  	 <h3>Добавить категорию</h3>
    </div>
  </div>

  <div class="row">
  	<!--<form action='{{ url("addCategory") }}' method="POST">
  		@csrf-->
  		<div class="form-group">
  			<label for="cat_name">Название категории</label>
  			<input type="hidden" name="_token" value="{{csrf_token()}}">
  			<input type="text" name="name" id="cat_name">
        <button class="btn btn-primary" onclick="addCategory(event)">Добавить</button>
  		</div>
  	<!--</form>-->
  </div>
  @endif
  @endif

  <div class="row">	
    <div class="col-10">
  	 <h3>Категории</h3>
    </div>
    <div class="col-10">
    <ul class="list-group" id="ul_cat">
      @if(isset($allCategories))
      @foreach($allCategories as $category)
      <li id="{{$category->id}}" class="list-group-item mb-2 w-50">{{$category->name}}
      @if (!Auth::guest() && Auth::user()->name == 'admin')
      <button class="btn-danger ml-1" onclick="deleteCategory('{{$category->id}}')">Удалить</button>

      <form action="{{ url('/updCategory/'.$category->id) }}" method="POST">
      @csrf
      <input type="text" name="upd_name" id="{{$category->id}}" value="{{$category->name}}">
      <button class="btn-success ml-1" type="submit">Обновить</button>
      </form>
      @endif
      </li>
      @endforeach
      @endif
    </ul>
    </div>
  </div>

  <div class="row">
  	<div id="text">
  		
  	</div>
  </div>

</div>
@endsection

<script>

function getCategories(newCategoryId, newCategoryName) {

    let li = document.createElement('LI');
    li.innerText = newCategoryName;
    li.className = 'list-group-item mb-2';
    li.id = `${newCategoryId}`;
    document.getElementById('ul_cat').prepend(li);

    delButton = document.createElement('BUTTON');
    delButton.innerText = 'Удалить'; 
    delButton.className = 'btn-danger ml-1';
    delButton.id = `delButton-${newCategoryId}`;
    li.appendChild(delButton);

    form = document.createElement('FORM');

    

    input_inv = document.createElement('INPUT');
     input_inv.type = 'hidden';
     input_inv.name = '_token';
     input_inv.value = `{{csrf_token()}}`;

    input = document.createElement('INPUT');
    input.type = 'text';
    input.name = 'upd_name';
    input.id = `${newCategoryId}`;
    input.value = `${newCategoryName}`;

    renewButton = document.createElement('BUTTON');
    renewButton.innerText = 'Обновить'; 
     renewButton.className = 'btn-success ml-1';
     renewButton.type="submit"
     renewButton.id = `delButton-${newCategoryId}`;

    form.appendChild(input_inv);
    form.appendChild(input);
    form.appendChild(renewButton);
   
     
    li.appendChild(form);


    

    document.getElementById(`delButton-${newCategoryId}`).addEventListener('click', function() {
      deleteCategory(newCategoryId);
    });
}

function deleteCategory(delId) {
  console.log(delId);
  let request = new XMLHttpRequest;
  let data = "category="+encodeURIComponent(delId);

  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementById('ul_cat').removeChild(document.getElementById(`${delId}`));
      document.getElementById('text').innerHTML = request.response.message;
      console.log(request.response);
    }
  }

  request.open('POST', '{{ url("delCategory") }}', true);
  request.responseType = 'json';

  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

  request.send(data);
  
}

function addCategory(e) {
	let request = new XMLHttpRequest();
  let data = "name="+encodeURIComponent(document.getElementById('cat_name').value);
  console.log(data);
  request.onreadystatechange = function() {
 
    if(request.readyState == 4) {
    	 	 
      if(request.status == 201) {
        let newCategory = request.response;
        document.getElementById('cat_name').value = '';

        if(newCategory) {
          getCategories(newCategory.id, newCategory.name);
        }
      }
      else {
          

               
       
      }
    }
  }

  request.open('POST', '{{ url("/addCategory") }}', true);
  request.responseType = 'json';

  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

  request.send(data);
             
}




</script>

