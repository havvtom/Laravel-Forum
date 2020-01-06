@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>
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
              <replies @removed="repliesCount--" @added="repliesCount++"></replies>            
           
          </div>
          <div class="col-md-4">
            <div class="card">
                  <div class="card-body">
                    This thread was published {{$thread->created_at->diffForHumans()}} by <a href="">{{$thread->owner->name}}</a>, and currently has <span v-text="repliesCount"></span> {{Str::plural('comment', $thread->replies_count)}}.
                  </div>
              </div>
          </div>
      </div>
      
  </div>
</thread-view>
@endsection