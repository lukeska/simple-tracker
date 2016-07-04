<template>

	<div class="months">
		<div v-if="!refreshingData">
			<month-component v-for="(index, monthlyActivities) in activities" 
					:name="monthNames[index]"
					:activities="monthlyActivities"
					:is-editable="isEditable"
					:monthly-view="monthlyView"
					:month-index="index"
			></month-component>
		</div>
		<div v-else>
			<loader :color="color">
		</div>
	</div>
	
</template>

<script>
	var MonthComponent = require( './month.vue' );
	var loader = require( './loader.vue' );
	import constants from '../constants/constants.vue';

	export default {
		props: ['activities', 'year', 'isEditable', 'color', 'refreshingData', 'monthlyView'],

		data: function() {
			return {
				monthNames: constants.MONTH_NAMES
			};
		},

		components: {
			MonthComponent,
			loader
		}
	}
</script>