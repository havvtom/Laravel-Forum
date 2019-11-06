@extends('layouts.app')

@section('content')
@if (count($errors) > 0)
   <div class = "alert alert-danger">
      <ul>
         @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
         @endforeach
      </ul>
   </div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="">{{$thread->owner->name}} </a> posted {{$thread->title}}
                </div>
                    
                <div class="card-body">
                  {{$thread->body}}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>
    @if(Auth::check())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{$thread->id.'/replies'}}">
                @csrf
               <div class="form-group">
                <label for="body">Body</label>
                <textarea name="body" id="body" class="form-control" rows=5 placeholder="Have something to say"></textarea>
               </div> 
               <button type="submit" class="btn btn-primary">Post</button>
            </form>
        </div>
    </div>
    @else
        <p class="text-center">Please <a href="{{route('login')}}">sign in </a>here to participate in this discussion</p>
    @endif
</div>
@endsection