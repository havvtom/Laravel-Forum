<script type="text/javascript">
	export default{
		props:['initialRepliesCount', 'data'],
		data(){
			return{
				repliesCount: this.data.replies_count,
				locked: this.data.locked,
			}
		}, 
		methods: {

			toggle(){

				// axios.post('/locked-threads/'+this.data.slug);
				this.locked =! this.locked;
				
			}
		},
		computed: {
			isAdmin(){
				if(window.App.user){
					return ['Tom', 'Sharon'].includes(window.App.user.name);
				}

					return false;
			}
		}, 
		created(){
			console.log(this.data.locked);
		}
	}
</script>