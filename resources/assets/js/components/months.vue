<template>

	<div class="months">
		<div v-if="!refreshingData">
			<month v-for="(index, monthlyActivities) in activities" 
					:name="monthNames[index]"
					:activities="monthlyActivities"
					:is-editable="isEditable"
					:monthly-view="monthlyView"
					:month-index="index"
			></month>
		</div>
		<div v-else>
			<loader :color="color">
		</div>
	</div>
	
</template>

<script>
	var month = require( './month.vue' );
	var loader = require( './loader.vue' );

	export default {
		props: ['activities', 'year', 'isEditable', 'color', 'refreshingData', 'monthlyView'],

		components: {
			month,
			loader
		}
	}
</script>