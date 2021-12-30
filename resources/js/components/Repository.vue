<template>
	<div class="card h-100">
		<div class="card-body">
			<h3 class="card-title fw-bold">{{ name }}</h3>
			<div class="d-flex align-items-center mb-2">
				<!-- Found via bootstrap icons, and they had the SVG elem ready for use w/o importing the entire lib. -->
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
					<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
				</svg>
				<p class='ms-2 mb-0'>{{ formattedStars }}</p>
			</div>

			<ul class="list-group list-group-flush my-3" v-show='toggleDetailedView'>
				<li class="list-group-item"><b>Name:</b> {{ name }}</li>
				<li class="list-group-item"><b>Number of Stars:</b> {{ formattedStars }}</li>
				<li class="list-group-item"><b>ID:</b> {{ id }}</li>
				<li class="list-group-item"><b>Description:</b> {{ description }}</li>
				<li class="list-group-item"><b>Created At:</b> {{ formattedCreatedAtDate }}</li>
				<li class="list-group-item"><b>Pushed At:</b> {{ formattedPushedAtDate }}</li>
				<li class="list-group-item"><b>URL:</b> <a target="_blank" :href="url">{{ url }}</a></li>
			</ul>

			<a href="#!" @click='toggleFullDetails'>({{ toggleDetailedView ? 'Hide' : 'Show' }} Detailed View)</a>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		name: {
			type: String,
			required: true
		},
		stars: {
			type: Number,
			required: true
		},
		id: {
			type: Number,
			required: true
		},
		description: {
			type: String,
			required: true
		},
		createdAt: {
			type: String,
			required: true
		},
		pushedAt: {
			type: String,
			required: true
		},
		url: {
			type: String,
			required: true
		}
	},

	data: function() {
		return {
			toggleDetailedView: false
		}
	},

	computed: {
		formattedCreatedAtDate() {
			return this.dateTimeFormat(this.createdAt);
		},

		formattedPushedAtDate() {
			return this.dateTimeFormat(this.pushedAt);
		},

		formattedStars() {
			return this.stars.toLocaleString();
		}
	}, 

	methods: {
		toggleFullDetails() {
			this.toggleDetailedView = !this.toggleDetailedView;
		},

		dateTimeFormat(dateTime) {
			const d = new Date(dateTime);
			const UTCDate = Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(), d.getUTCHours(), d.getUTCMinutes());

			const date = new Intl.DateTimeFormat('en', 
				{
					month: 'long',
					day: 'numeric',
					year: 'numeric',
					hour: 'numeric',
					minute: 'numeric',
				} 
			)
			.format(UTCDate);

			return date + ' UTC';
		}
	}
}
</script>