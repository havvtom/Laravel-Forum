<template>
	<!-- <reply :attributes="{{$reply}}" inline-template v-cloak> -->
    <div class="card" :id="'reply'+id">
        <div class="card-header">
        	<div class="level">
        		<h5 class="flex">
        		<a :href="'/profile/'+data.user.name" v-text="data.user.name"></a> said {{data.created_at}}
        		</h5>
        		<!-- <div>
                    @if(Auth()->check())
                        <favorite :reply="{{$reply}}">
                            
                        </favorite>
        			@endif
        		</div> -->
        	</div>
           
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing=false">Cancel</button>
            </div>
            <div v-else v-text="body">
                <!-- {{$reply->body}}  -->  
            </div>
        </div>
        <!-- @can('update', $reply)
        <div class="card-footer level">
            <button class="btn btn-info btn-xs mr-1" @click="editing=true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
        </div>
        @endcan -->
    </div>
	<!-- </reply> -->
</template>
<script type="text/javascript">
	export default{
		props:['data'],
		data(){
			return {
				editing: false,
				body: this.data.body,
				id: this.data.id

			}
		},
		methods:{
			update(){
				axios.patch('/replies/'+this.data.id, {
					body: this.body
				});

				this.editing = false;

				flash('Updated!');
			},

			destroy(){
				axios.delete('/replies/' + this.data.id);

				$(this.$el).fadeOut(3000,()=>{
					flash('You reply has been deleted!');
				});
				
			}
		}
	}
</script>