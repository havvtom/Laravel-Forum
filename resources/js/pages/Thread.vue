<script type="text/javascript">
	export default{
		props:['initialRepliesCount', 'data'],
		data(){
			return{
				repliesCount: this.data.replies_count,
				locked: this.data.locked,
				editing: false,
				body: this.data.body,
				title: this.data.title,
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
			cancel(){

				this.editing = false;
				this.form.body = this.data.body;
				this.form.title = this.data.title;

			},

			update(){

				axios.patch('/threads/'+this.data.channel.slug+'/'+this.data.slug, 
					this.form
				)
				.then(()=> {

					this.editing = false;
					this.title = this.form.title;
					this.body = this.form.body;
					flash('Your thread has been updated');})
			}, 
			
		},
		computed: {
			isAdmin(){
				if(window.App.user){
					return ['Tom', 'Sharon'].includes(window.App.user.name);
				}

					return false;
			},
			owns(){
				if(window.App.user){

					return this.data.user_id === window.App.user.id;
				}
				return false;
			}
		}, 
		created(){
			// console.log(this.data.user_id,window.App.user.id);
		}
	}
</script>