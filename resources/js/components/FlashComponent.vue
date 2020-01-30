<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-flash" :class="'alert-' + level" role="alert" v-show='show' v-text="body">
                  
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['message'],
        data(){
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },
        methods: {
            flash(data){
                if(data){

                    this.body = data.message;
                    this.level = data.level;
                }
                
                this.show = true;
                this.hide();
            },
            hide(){
                setTimeout(()=>{ 
                    this.show = false }, 3000);
            }
        },
        created(){
            if(this.message){
                this.flash();
            }

            window.events.$on('flash', (data)=>{
                this.flash(data);
            });
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style>
    .alert-flash{
        position: fixed;
        right: 25px;
        bottom:25px;
    }
</style>
