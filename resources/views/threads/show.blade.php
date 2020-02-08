@extends('layouts.app')

@section('content')
<thread-view :data="{{$thread}}" inline-template>
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
          <div class="col-md-8" v-cloak>
              @include('threads._question')

              <replies @removed="repliesCount--" @added="repliesCount++" v-if="! editing"></replies>            
           
          </div>
          <div class="col-md-4">
            <div class="card">
                  <div class="card-body">
                    <p>This thread was published {{$thread->created_at->diffForHumans()}} by <a href="">{{$thread->owner->name}}</a>, and currently has <span v-text="repliesCount"></span> {{Str::plural('comment', $thread->replies_count)}}.
                    </p>
                    <p>
                      <subscribe-button :active="{{$thread->isSubscribedTo ? 'true' : 'false'}}"></subscribe-button>
                      <button class="btn btn-outline-secondary ml-2" v-if="isAdmin" @click="toggle" v-text="locked ? 'UnLock' : 'Lock'"></button>
                    </p>
                  </div>
              </div>
          </div>
      </div>
      
  </div>
</thread-view>
@endsection