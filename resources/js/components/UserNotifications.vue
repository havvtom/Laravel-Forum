<template>
	<li class="nav-item dropdown" v-if="notifications.length">
	    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-v-pre>
	                    <span class="caret">Notifications</span>
	    </a>                                

    	<ul  class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" >
    		<li class="dropdown-item" v-for="notification in notifications">
    			<a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a> 
    		</li>                              
    	</ul>
    </li>
</template>

<script type="text/javascript">

	export default{
		data(){
			return{
				notifications: false
			}
		},

		created(){

			axios.get('/profiles/'+window.App.user.name+'/notifications')
			.then((response)=>
				{this.notifications = response.data}
			);
		},

		methods: {
// '/profiles/'.Auth()->user()->name.'/notifications/'.$notificationId
			markAsRead(notification){

				axios.delete('/profiles/'+ window.App.user.name +'/notifications/' + notification.id)

			}
		}
	}

</script>