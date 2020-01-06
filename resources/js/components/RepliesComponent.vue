<template>
	<div>
		<div v-for="(reply, index) in items" :key="reply.id">
			<reply :data = "reply" @delete="remove(index)" ></reply>
		</div>
		<new-reply :endpoint="endpoint" @created="add"></new-reply>
	</div>
</template>
<script type="text/javascript">
	export default{
		props:['data'],
		data(){
			return{
				items:this.data,
				endpoint: location.pathname + '/replies'
			}
		},
		methods:{
			remove(index){
				this.items.splice(index, 1);
				this.$emit('removed');
				flash('Reply was deleted');
			},
			add(reply){
				this.items.push(reply);
				this.$emit('added');
			}
		}
	}
</script>