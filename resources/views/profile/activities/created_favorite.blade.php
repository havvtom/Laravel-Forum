@component('profile.activities.activity')

	@slot('heading')
		<a href="{{$activity->subjectable->favoritable->path()}}">{{$profileUser->name}} favorited a reply.</a> 
				
	@endslot
	@slot('body')
		{{$activity->subjectable->favoritable->body}}
	@endslot

@endcomponent