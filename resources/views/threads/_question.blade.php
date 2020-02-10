<!-- EDITING -->

<div class="card" v-if="editing">
  
    <div class="card-header">
      <div class='level'>
        
        <input class="form-control" type="text" name="title" v-model="form.title">
        
          
        
      </div>
    </div>
        
    <div class="card-body">
      <div class="form-group">
        <textarea class="form-control" rows="10" v-model="form.body"></textarea>
      </div>
    </div>
    <div class="card-footer">
      <div class="level">
        <button class="btn btn-outline-secondary btn-xs mr-2" v-if="!editing" @click="editing=true">Edit</button>
        <button class="btn btn-primary btn-xs mr-2" @click="update" >Update</button>
        <button class="btn btn-outline-secondary btn-xs" @click="cancel">Cancel</button>
        @can('update', $thread)
          <form action="{{basename($thread->path())}}" method="POST" class="ml-a">
            @csrf
            @method('DELETE')
            <button class="btn btn-link">Delete Thread</button>
          </form>
          @endcan
      </div>
    </div>
</div>

<!-- NOT EDITING -->

<div class="card" v-else>
    <div class="card-header">
      <div class='level'>
        <img src="{{$thread->owner->avatar_path}}" width="50" height="50"class="mr-1">
        <span class="flex"><a href="{{route('profile', $thread->owner->name)}}">{{$thread->owner->name}} </a> posted: <a href="{{route('thread', [$thread->channel->slug, $thread->slug])}}" v-text="title"></a></span>
        
      </div>
    </div>
        
    <div class="card-body">
      <span v-text="body"></span>
    </div>
    <div class="card-footer" v-if="owns">
      <button class="btn btn-outline-secondary btn-xs" @click="editing=true" >Edit</button>
    </div>
</div>