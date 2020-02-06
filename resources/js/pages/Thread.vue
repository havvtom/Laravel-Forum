<script type="text/javascript">
	export default{
		props:['initialRepliesCount', 'dataLocked'],
		data(){
			return{
				repliesCount: this.initialRepliesCount,
				locked: this.dataLocked,
			}
		}, 
		computed: {
			isAdmin(){
				if(window.App.user){
					return ['Tom', 'Sharon'].includes(window.App.user.name);
				}

					return false;
			}
		}
	}
</script>