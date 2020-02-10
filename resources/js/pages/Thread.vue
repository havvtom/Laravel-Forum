<script type="text/javascript">
	export default{
		props:['initialRepliesCount', 'data'],
		data(){
			return{
				repliesCount: this.data.replies_count,
				locked: this.data.locked,
				editing: false,
				form: {
					title: this.data.title,
					body: this.data.body
				},
			}
		}, 
		methods: {

			toggle(){

				axios[this.locked ? 'delete' : 'post']('/locked-threads/'+this.data.slug);
				
				this.locked =! this.locked;
				
			},

			update(){

				axios.patch('/threads/'+this.data.channel.slug+'/'+this.data.slug, {
					title: this.data.title,
					body: this.data.body
				})
				.then(()=> {flash('Your thread has been updated');})
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
			console.log(this.data.channel.slug);
		}
	}
</script>