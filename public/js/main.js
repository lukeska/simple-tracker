var Vue = require( 'vue' );
var MonthsComponent = require( './components/months.vue' );
var trackerheader = require( './components/trackerheader.vue' );
var loader = require( './components/loader.vue' );

// Use and configure vue-resource.
Vue.use( require( 'vue-resource' ) );
Vue.http.options.emulateJSON = true;
Vue.http.options.emulateHTTP = true;

// Create a global mixin to expose strings, global config, and single backend resource.
Vue.mixin( {
	computed: {
		nonce: function() {
			return SimpleTracker.nonce;
		},
		resource: function() {
			return this.$resource( SimpleTracker.ajaxurl );
		}
	}
} );



var store = {
	activities: [],
	targetId: 0,
	title: '',
	content: '',
	year: new Date().getFullYear(),
	minYear: 2015,
	color: '',
	dataLoaded: false,
	refreshingData: false,
	isEditable: false,
	monthlyView: true
};

new Vue({
	el: '#simple-tracker',

	data: store,

	components: {
		Months: MonthsComponent,
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
					obj = JSON.parse(response.data);
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

		formatDate: function(date) {
			let month = date.getMonth() + 1;
			month = (month < 10) ? '0' + month : month;
			let day = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate()
			return '' + date.getFullYear() + month + day;
		},

		initActivities: function(year, trackingData = {}) {

			let result = [];

			for (var m = 0; m <= 11; m++) {
				days = new Date(year, m+1, 0).getDate();

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
		}
	},

	events: {
		'navigate-year': function(value) {
			this.year += value;
			this.refreshData();
		},

		'day-result-set': function(activity) {
			console.log(activity)
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
		this.targetId = this.$el.dataset.target_id;
		this.isEditable = (this.$el.dataset.is_editable == 1) ? true : false;
		this.refreshData();
	}
});