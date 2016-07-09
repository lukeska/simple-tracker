var Vue = require( 'vue' );
var simpletracker = require( './components/simpletracker.vue' );

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
		},
		trackerswitchyear: function() {
			return SimpleTracker.trackerswitchyear;
		},
		trackerswitchmonth: function() {
			return SimpleTracker.trackerswitchmonth;
		},
		trackerinfo: function() {
			return SimpleTracker.trackerinfo;
		},
		trackerinfourl: function() {
			return SimpleTracker.trackerinfourl;
		},
		pluginurl: function() {
			return SimpleTracker.pluginurl;
		},
		monthNames: function() {
			return SimpleTracker.monthnames;
		},
		dayNames: function() {
			return SimpleTracker.daynames;
		}
	}
} );

new Vue({
	el: 'body',

	components: {
		simpletracker: simpletracker
	},
});