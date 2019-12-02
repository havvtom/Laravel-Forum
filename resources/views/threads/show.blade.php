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
                  <div class='level'>
                    <span class="flex"><a href="{{route('profile', $thread->owner->name)}}">{{$thread->owner->name}} </a> posted: <a href="{{route('thread', [$thread->channel->slug, $thread->id])}}">{{$thread->title}}</a></span>
                    <span>
                      @can('update', $thread)
                      <form action="{{basename($thread->path())}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-link">Delete Thread</button>
                      </form>
                      @endcan
                    </span>
                  </div>
                </div>
                    
                <div class="card-body">
                  {{$thread->body}}
                </div>
            </div>

            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach
            <div style="margin-top: 30px;">{{$replies->links()}}</div>
    
    @if(Auth::check())

            <form method="POST" action="{{$thread->id.'/replies'}}">
                @csrf
               <div class="form-group">
                <label for="body">Body</label>
                <textarea name="body" id="body" class="form-control" rows=5 placeholder="Have something to say"></textarea>
               </div> 
               <button type="submit" class="btn btn-primary">Post</button>
            </form>
        </div>
        <div class="col-md-4">
          <div class="card">
                <div class="card-body">
                  This thread was published {{$thread->created_at->diffForHumans()}} by <a href="">{{$thread->owner->name}}</a>, and currently has {{$thread->replies_count}} {{Str::plural('comment', $thread->replies_count)}}.
                </div>
            </div>
        </div>
    </div>
    @else
        <p class="text-center">Please <a href="{{route('login')}}">sign in </a>here to participate in this discussion</p>
    @endif
</div>
@endsection