<template>
	<div>
		<div class="st-header" v-if="dataLoaded">
			<h2 class="st-title" :style="{ backgroundColor: color }">{{ title }}</h2>
			<div class="st-year-navigator">
				<a href="" class="st-year-navigator-btn st-year-navigator-btn-prev"
					v-show="(minYear < year) && !monthlyView"
					@click.prevent="addYear(-1)"
				></a>
				<span class="st-year-navigator-current">{{ year }}</span>
				<a href="" class="st-year-navigator-btn st-year-navigator-btn-next"
					v-show="(year < currentYear) && !monthlyView"
					@click.prevent="addYear(1)"
				></a>
				<a href="" class="st-year-navigator-btn st-year-navigator-btn-view-switch"
					@click.prevent="switchView()"
				></a>
				<a href="" class="st-year-navigator-btn st-year-navigator-btn-info"

				></a>
			</div>
		</div>

		<div class="st-content" v-if="dataLoaded && !monthlyView">{{ content }}</div>
	</div>
</template>

<script>
	export default {
		props: ['year', 'minYear', 'color', 'title', 'content', 'dataLoaded', 'monthlyView'],

		data: function() {
			return {
				currentYear: new Date().getFullYear()
			}
		},

		methods: {
			addYear: function(value) {
				this.$dispatch('navigate-year', value);
			},

			switchView: function() {
				this.$dispatch('switch-view', !this.monthlyView);
			}
		},
	}
</script>
