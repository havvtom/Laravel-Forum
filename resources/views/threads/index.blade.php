@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse($threads as $thread)
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <h4 class="flex">
                            <a href="{{route ('thread', [$thread->channel->slug, $thread->id])}}">
                                @if($thread->hasUpdatesFor(Auth()->user()))

                                    <strong>{{$thread->title}}</strong>

                                @else

                                    {{$thread->title}}

                                @endif
                            </a>
                        </h4>
                        <strong><a href="{{route ('thread', [$thread->channel->slug, $thread->id])}}">{{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count)}}</a></strong>
                    </div>
                </div>
                <div class="card-body">                   
                    <div class="body">
                        {{$thread->body}}
                    </div>
                    <hr>                   
                </div>
            </div>
            @empty
            <p>There is nothing to show at the moment...</p>
            @endforelse
        </div>
    </div>
</div>
@endsection