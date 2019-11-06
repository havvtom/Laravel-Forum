@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a Thread</div>

                <div class="card-body">
                   <form method="POST" action="/threads">
                       @csrf
                       <div class="form-group">
                            <label for="channel">Channel</label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                              <option selected>Open this select menu</option>
                              @foreach($channels as $channel)
                              <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected' : ''}} >{{$channel->name}}</option>
                              @endforeach
                            </select>
                        </div>
                       <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter thread title" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="thread">Thread</label>
                            <textarea type="text" class="form-control" id="thread" name="body" placeholder="Type here" rows=8 required>{{old('body')}}</textarea>
                        </div>
                        <div class="form-group">
                        <button type="submit" class="btn btn-primary">Publish</button>
                        </div>
                        @if (count($errors) > 0)
                           <div class = "alert alert-danger">
                              <ul>
                                 @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                 @endforeach
                              </ul>
                           </div>
                        @endif
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection