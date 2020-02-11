@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @include('threads._list')

            {{$threads->render()}}
        </div>
        <div class="col-md-4">
            <div class="card">                
                <div class="card-body">
                    <form method="GET" action="/threads/search" class="form-inline my-2 my-lg-0">
                      <input class="form-control mr-2 col-md-8" type="text" name="q" placeholder="Search" aria-label="Search">
                      <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
                
            </div>
        	@if(count($trending))
        	<div class="card">
        		<div class="card-header">
        			Trending Threads
        		</div>
        		<div class="card-body">
        			<ul class="list-group">
        			@foreach($trending as $thread)
        				<li class="list-group-item">
        					<a href="{{$thread->path}}">
        						{{$thread->title}}
        					</a>
        				</li>
        			@endforeach
        			</ul>
        			@endif
        		</div>
        		
        	</div>
        </div>
    </div>
</div>
@endsection