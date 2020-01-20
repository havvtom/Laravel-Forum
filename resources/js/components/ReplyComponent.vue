<template>
	<!-- <reply :attributes="{{$reply}}" inline-template v-cloak> -->
    <div class="card" :id="'reply'+id">
        <div class="card-header">
        	<div class="level">
        		<h5 class="flex">
        		<a :href="'/profiles/'+data.user.name" v-text="data.user.name"></a> said <span v-text="ago"></span>
        		</h5>
        		<div v-if="signedIn">
                    
                        <favorite :reply="data">
                            
                        </favorite>
        			
        		</div>
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
            <div v-else v-html="body">
                <!-- {{$reply->body}}  -->  
            </div>
        </div>
       <!--  @can('update', $reply) -->
        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-info btn-xs mr-1" @click="editing=true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
        </div>
        <!-- @endcan -->
    </div>
	<!-- </reply> -->
</template>
<script type="text/javascript">
	import moment from 'moment';
	export default{
		props:['data'],
		data(){
			return {
				editing: false,
				body: this.data.body,
				id: this.data.id
			}
		},
		computed:{
			signedIn(){
				return window.App.signedIn;
			},
			canUpdate(){

				return this.authorize(user => this.data.user.id == user.id);
				// return window.App.user.id == this.data.user.id;
			},
			ago(){
				return moment(this.data.created_at).fromNow()+"...";
			}
		},
		methods:{
			update(){
				axios.patch('/replies/'+this.data.id, {
					body: this.body
				})
				.then(response=>{
					this.editing = false;

					flash('Updated!');
				})
				.catch(error=>{
					flash(error.response.data, 'danger')
				});

				
			},

			destroy(){
				axios.delete('/replies/' + this.data.id);

				this.$emit('delete', this.data.id);
				// $(this.$el).fadeOut(3000,()=>{
				// 	flash('You reply has been deleted!');
				// });
				
			}
		}
	}
</script>