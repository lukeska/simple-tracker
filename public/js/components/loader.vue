<template>
	<div class="spinner-container">
		<div class="spinner">
			<div class="rect1" :style="{ backgroundColor: color }"></div>
			<div class="rect2" :style="{ backgroundColor: color }"></div>
			<div class="rect3" :style="{ backgroundColor: color }"></div>
			<div class="rect4" :style="{ backgroundColor: color }"></div>
			<div class="rect5" :style="{ backgroundColor: color }"></div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['color']
	}
</script>