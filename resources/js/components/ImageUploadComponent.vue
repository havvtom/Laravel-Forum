<template>
	
	<input type="file" accept="image/*" @change="onChange">

</template>
<script type="text/javascript">
	export default{

		methods: {
			onChange(e){

				if( ! e.target.files.length) return;

				let file = e.target.files[0];

				let reader = new FileReader();

				reader.readAsDataURL(file);

				reader.onload = e => {
					let src = e.target.result;
					this.$emit('loaded', {file, src});

				};				

			},
		}

	}
</script>