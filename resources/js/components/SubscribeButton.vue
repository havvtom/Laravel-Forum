<template>
	
	<button :class="classes" @click="subscribe" v-if="signedIn">Subscribe</button>
	
</template>

<script type="text/javascript">
	export default{
		props: ['active'],
		data(){

			return{

				
			}
		},

		computed:{

			classes(){

				return ['btn', this.active ? 'btn-primary ?' : 'btn-secondary'];
			}
		},

		methods: {

			subscribe(){

				axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions');

				this.active = !this.active;

			}
		}

	}
</script>