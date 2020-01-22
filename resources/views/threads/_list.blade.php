@forelse($threads as $thread)
            <div class="card">
                <div class="card-header">
                    <div class="level">
                       <div class="flex">
                        <h4>
                            <a href="{{route ('thread', [$thread->channel->slug, $thread->id])}}">
                                @if(Auth()->check() && $thread->hasUpdatesFor(Auth()->user()))

                                    <strong>{{$thread->title}}</strong>

                                @else

                                    {{$thread->title}}

                                @endif
                            </a>
                        </h4>

                        <h5>
                            
                            Posted by <a href="{{route('profile', $thread->owner->name)}}">{{$thread->owner->name}}</a>

                        </h5>
                       </div>
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