<template>
	<div>
			<div v-if="signedIn">
                 <div class="form-group">
                  <label for="body">Body</label>
                  <textarea name="body" 
                  			id="body" 
                  			class="form-control" 
                  			rows=5 placeholder="Have something to say" 
                  			v-model="body"
                  			required
                  			>
                  				
                  </textarea>
                 </div> 
                 <button type="submit" class="btn btn-primary" @click="addReply">Post</button>           
               
            </div>
             <p v-else class="text-center">Please <a href="/login">sign in </a>here to participate in this discussion</p>
              
	</div>
</template>
<script type="text/javascript">
	export default{
		
		data(){
			return{
				body:     '',
			}
		},
		computed:{
			signedIn(){
				return window.App.signedIn;
			}
		},
		methods:{
			addReply(){
				axios.post(location.pathname + '/replies', {body:this.body})
				.then(response =>{
					this.body = "";

					flash('Your reply has been posted');

					this.$emit('created', response.data)
				})
			}
		}

	}
</script>