<template>
	<div>
		<div class="month {{ (monthlyView) ? 'month-single' : '' }}" v-show="isVisible">
			<span class="month-name">{{ name }}</span>

			<div class="month-days">
				<ul>
					<li v-for="activity in activities">
						<day-component :activity="activity" :is-editable="isEditable"></DayComponent>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>

<script>
	var DayComponent = require( './day.vue' );

	export default {
		props: ['name', 'activities', 'monthlyView', 'monthIndex', 'isEditable'],

		components: {
			DayComponent
		},

		computed: {
			isVisible: function() {
				if(this.monthlyView == false) return true;

				return (this.monthIndex == new Date().getMonth()) ? true : false;
			}
		}

	}
</script>