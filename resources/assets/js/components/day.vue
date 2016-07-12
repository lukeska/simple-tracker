<template>
	<div class="day 
				day-{{ dayClass }} 
				{{ (isFuture) ? 'day-future' : '' }} 
				{{ (isToday) ? 'day-today' : '' }}
				{{ (isUpdating) ? 'day-updating' : '' }}">
		<div class="day-controls">
			<span class="day-date">{{ formatteDateWithDayName }}</span>
			<div class="day-controls-buttons" v-if="!isFuture && isDayEditable">
				<a href="" class="day-controls-button-reset" @click.prevent="setDayResult(activity.date, 0)">Reset</a><br />
				<a href="" class="day-controls-button day-controls-button-good" @click.prevent="setDayResult(activity.date, 10)">Bene</a>
				<a href="" class="day-controls-button day-controls-button-bad" @click.prevent="setDayResult(activity.date, 1)">Male</a>
			</div>
		</div>
		<a class="day-result {{ (isSunday) ? 'day-result-week-number' : '' }}" @click.prevent="setDayResult(activity.date)" v-if="isDayEditable && !isFuture">
			{{ activity.date.getDate() }}
		</a>
		<span class="day-result {{ (isSunday) ? 'day-result-week-number' : '' }}" v-else>
			{{ activity.date.getDate() }}
		</span>
	</div>
</template>

<script>

	export default {
		props: ['activity', 'isEditable'],

		data: function() {
			return {
				isUpdating: false,
				isDayEditable: (this.isEditable == "1") ? true : false 
			}
		},

		methods: {
			setDayResult: function(date, result = null) {

				if(result == null) {
					switch (this.activity.result) {
						case 0:
							result = 10;
							break;
						case 1:
							result = 0;
							break;
						case 10:
							result = 1;
							break;
					}
				}

				if(this.activity.result == result) return;

				this.activity.result = result;
				this.isUpdating = true;
				this.$dispatch('day-result-set', this.activity);
			},

			weekNumber: function(date){
				var d = new Date(+date);
				d.setHours(0,0,0);
				d.setDate(d.getDate()+4-(d.getDay()||7));
				return Math.ceil((((d-new Date(d.getFullYear(),0,1))/8.64e7)+1)/7);
			}
		},

		computed: {
			isSunday: function() {
				return (this.activity.date.getDay() == 0) ? true : false;
			},

			isToday: function() {
				if(this.activity.date.toDateString() == new Date().toDateString()) return true;
				else return false;
			},

			isFuture: function() {
				if(this.activity.date > new Date()) return true;
				else return false;
			},

			formattedDate: function() {
				return ((this.activity.date.getDate() < 10) ? '0' : '') + this.activity.date.getDate() + '/' 
					+ (((this.activity.date.getMonth()+1) < 10) ? '0' : '') + (this.activity.date.getMonth()+1) + '/' 
					+ this.activity.date.getFullYear();
			},

			formatteDateWithDayName: function() {
				return this.dayNames[this.activity.date.getDay()] + ' ' 
					+ this.activity.date.getDate() + ' ' + this.monthNames[this.activity.date.getMonth()];
			},

			dayClass: function() {
				switch(this.activity.result) {
					case 10:
						return 'good';
					case 1:
						return 'bad';
					default:
						return 'default';
				}
			}
		},

		events: {
			'day-result-updated': function() {
				this.isUpdating = false;
			}
		}
	}
</script>