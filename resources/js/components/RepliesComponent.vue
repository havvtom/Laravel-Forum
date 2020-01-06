<template>
	<div>
		<div v-for="(reply, index) in items" :key="reply.id">
			<reply :data = "reply" @delete="remove(index)" ></reply>
		</div>

		<paginator></paginator>
		
		<new-reply :endpoint="endpoint" @created="add"></new-reply>
	</div>
</template>
<script type="text/javascript">
	import collection from "../mixins/collection";
	export default{
		mixins:[collection],
		created(){
			this.fetch();
		},
		data(){
			return{
				dataSet:false,
				endpoint: location.pathname + '/replies'
			}
		},
		methods:{
			fetch(){
				axios.get(this.url())
				.then(this.refresh);
			},
			refresh({data}){
				this.dataSet = data;
				this.items = data.data
			},
			url(){
				return `${location.pathname}/replies`;
			},
			
		}
	}
</script>