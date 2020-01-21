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
	import Tribute from 'tributejs';
	export default{
		
		data(){
			return{
				body:     '',
				values : []
			}
		},
		computed:{
			signedIn(){
				return window.App.signedIn;
			}
		},
		mounted(){
			var tribute = new Tribute({

			  values: function (text, cb) {

    			remoteSearch(text, users => cb(users));

  					},
  					lookup: 'name',

  					fillAttr: 'name'
				});

			function remoteSearch(text, cb) {
				  var URL = '/api/users';
				  var xhr = new XMLHttpRequest();
				  xhr.onreadystatechange = function() {
				    if (xhr.readyState === 4) {
				      if (xhr.status === 200) {
				        var data = JSON.parse(xhr.responseText);
				        cb(data);
				      } else if (xhr.status === 403) {
				        cb([]);
				      }
				    }
				  };
				  xhr.open("GET", URL + "?name=" + text, true);
				  xhr.send();
				};
			
  	tribute.attach(document.getElementById("body"));

			
		},
		methods:{
			addReply(){
				axios.post(location.pathname + '/replies', {body:this.body})
				.then(response =>{
					this.body = "";

					flash('Your reply has been posted');

					this.$emit('created', response.data)
				})
				.catch(error=>{
					flash(error.response.data, 'danger');
				})
			},

			
						}

	}
</script>