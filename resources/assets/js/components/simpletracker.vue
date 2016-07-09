<template>
	<div class="simple-tracker-wrapper">
		<div id="simple-tracker" :target_id="target_id" :is-editable="isEditable">
			<div v-if="dataLoaded">
				<trackerheader :min-year="minYear" :year="year" :color="color" :title="title" :data-loaded="dataLoaded" :content="content" :monthly-view="monthlyView"></trackerheader>
				<months :activities="activities" :year="year" :is-editable="isEditable" :refreshing-data="refreshingData" :color="color" :monthly-view="monthlyView" ></months>
			</div>
			<div class="simple-tracker-loading" v-else><loader :color="color"></loader></div>
		</div>
	</div>
</template>

<script>
	var months = require( './months.vue' );
	var trackerheader = require( './trackerheader.vue' );
	var loader = require( './loader.vue' );

	export default {
		props: ['targetId', 'isEditable', 'color'],

		data: function() {
			return {
				activities: [],
				title: '',
				content: '',
				year: new Date().getFullYear(),
				minYear: 2015,
				dataLoaded: false,
				refreshingData: false,
				monthlyView: true
			}
		},

		components: {
			months: months,
			trackerheader: trackerheader,
			loader: loader
		},

		methods: {
			refreshData: function() {
				this.refreshingData = true;

				this.resource.get( {
					action: 'get_target_data',
					target_id: this.targetId,
					year: this.year,
					nonce: this.nonce
				} ).then( function( response ) {

					if(response.data != "false") {
						let obj = JSON.parse(response.data);
						this.title = obj.title;
						this.content = obj.content;
						this.color = obj.color;
						this.activities = this.initActivities(this.year, obj.tracking_data);

						this.dataLoaded = true;
						this.refreshingData = false;
					}

				}, function( response ) {
					// Log error.
					console.log( response );
					this.refreshingData = false;
				} );
			},

			initActivities: function(year, trackingData = {}) {

				let result = [];

				for (var m = 0; m <= 11; m++) {
					let days = new Date(year, m+1, 0).getDate();

					let monthDays = [];

					for (var d = 1; d <= days; d++) {
						let month = m + 1;
							month = (month < 10) ? '0' + month : '' + month;
						let day = (d < 10) ? '0' + d : d; 
						let rawDate = year + month + day;

						monthDays.push({
							date: new Date(year, m, d),
							result: (typeof trackingData[rawDate] !== "undefined") ? parseInt(trackingData[rawDate]) : 0
						});
					}

					result.push(monthDays);
				}
				
				return result;
			},

			formatDate: function(date) {
				let month = date.getMonth() + 1;
				month = (month < 10) ? '0' + month : month;
				let day = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate()
				return '' + date.getFullYear() + month + day;
			},
		},

		events: {
			'navigate-year': function(value) {
				this.year += value;
				this.refreshData();
			},

			'day-result-set': function(activity) {

				this.resource.save( {
					action: 'update_target_data',
					target_id: this.targetId,
					activity_date: this.formatDate(activity.date),
					activity_result: activity.result,
					nonce: this.nonce
				} ).then( function( response ) {
					
					this.$broadcast('day-result-updated', activity.date);

				}, function( response ) {
					// Log error.
					console.log( response );
				} );
			},

			'switch-view': function(value) {
				this.monthlyView = value;
			}
		},

		ready: function() {
			this.targetId = this.targetId;
			this.refreshData();
		}
	}
</script>