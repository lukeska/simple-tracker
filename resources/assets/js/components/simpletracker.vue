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
	var months = require( './components/months.vue' );
	var trackerheader = require( './components/trackerheader.vue' );
	var loader = require( './components/loader.vue' );
</script>