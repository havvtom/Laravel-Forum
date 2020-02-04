<template>

	<button type="submit" :class="classes" @click="toggle">
		<span class="glyphicon glyphicon-apple"></span>
		<span v-text="favoriteCount"></span> 
	</button>

</template>
<script type="text/javascript">
	export default{
		props : ['reply'],

		computed:{

			classes(){

				return ['btn', this.isFavorited ? 'btn-primary' : 'btn btn-outline-secondary']
			},

			endpoint(){
				return '/replies/' + this.reply.id + '/favorites';
			}
		},
		data(){
			return{
				favoriteCount:this.reply.favoritesCount,
				isFavorited: this.reply.isFavorited,
			}
		},
		methods:{
			toggle(){
				return this.isFavorited ? this.destroy() : this.create();
			},
			create(){
				axios.post(this.endpoint);
					this.isFavorited = true;
					this.favoriteCount ++;
				},
			destroy(){
				axios.delete(this.endpoint);
					this.isFavorited = false;
					this.favoriteCount --;
			}
		}

	}
</script>