<template>
	<div>
		<div class="level">

			<img :src="avatar" width="50" height="50" class="mr-1">

			<h1 v-text="user.name">			
			
			</h1>
			
		</div>
		

			<form v-if="canUpdate" method="POST" action="" enctype="multipart/form-data">

				<image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
				
				
			</form>


		
	</div>
</template>
<script type="text/javascript">
	import ImageUpload from "./ImageUploadComponent.vue";
	export default{

		components: {

			'image-upload' : ImageUpload
		},
		props: ['user'],

		data(){
			return {

				avatar: this.user.avatar_path

			}
		},

		computed: {

			canUpdate(){

				return this.authorize( user => user.id === this.user.id)

			}
		},

		methods: {

			onLoad(avatar){

				this.avatar = avatar.src;

				//persist to the server
				this.persist(avatar.file);
			},

			persist(avatar){

				let data = new FormData();

				data.append('avatar', avatar)

				axios.post('/api/users/' + this.user.name +'/avatar',  data)
				.then(()=> flash('Image persisted'));
			}

		}
	}
</script>