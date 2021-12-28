<template>
	<div class="card h-100 bg-light">
		<div class="card-body">
			<h3 class="card-title fw-bold">{{ name }}</h3>
			<p class="card-subtitle text-muted">ID: {{ id }}</p>
			<p class="card-text">{{ description }}</p>
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
		id: {
			type: Number,
			required: true
		},
		description: {
			type: String,
			required: true
		}
	},
}
</script>