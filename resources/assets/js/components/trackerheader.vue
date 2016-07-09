<template>
	<div>
		<div class="st-header" v-if="dataLoaded">
			<h2 class="st-title" :style="{ backgroundColor: color }">{{ title }}</h2>
			<div class="st-year-navigator">
				<a href="" class="st-year-navigator-btn st-year-navigator-btn-prev"
					v-show="(minYear < year) && !monthlyView"
					@click.prevent="addYear(-1)"
				>
					<img :src="leftarrowIconUrl" width="20" />
				</a>
				<span class="st-year-navigator-current">{{ year }}</span>
				<a href="" class="st-year-navigator-btn st-year-navigator-btn-next"
					v-show="(year < currentYear) && !monthlyView"
					@click.prevent="addYear(1)"
				>
					<img :src="rightarrowIconUrl" width="20" />
				</a>
				<div class="st-year-navigator-btn-container">
					<div class="st-year-navigator-btn-tooltip">
						<span v-if="monthlyView">{{ trackerswitchyear }}</span>
						<span v-else>{{ trackerswitchmonth }}</span>
					</div>
					<a href="" class="st-year-navigator-btn st-year-navigator-btn-view-switch"
						@click.prevent="switchView()"
					>
						<img :src="calendarIconUrl" width="20" />
					</a>
				</div>
				<div class="st-year-navigator-btn-container">
					<div class="st-year-navigator-btn-tooltip">
						<span>{{{ trackerinfo }}}</span>
					</div>
					<!--a href="{{ trackerinfourl }}" class="st-year-navigator-btn st-year-navigator-btn-info"
						target="_blank" 
					>
						<img :src="informationIconUrl" width="20" />
					</a-->
				</div>
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

		computed: {
			calendarIconUrl: function() {
				return this.pluginurl + '/img/calendar.svg';
			},
			informationIconUrl: function() {
				return this.pluginurl + '/img/information.svg';
			},
			rightarrowIconUrl: function() {
				return this.pluginurl + '/img/right-arrow.svg';
			},
			leftarrowIconUrl: function() {
				return this.pluginurl + '/img/left-arrow.svg';
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
