<template>
	<nav aria-label="Repository Pagination">
		<ul class="pagination">

			<li class="page-item" :class="{disabled: currentPage == 1}">
				<a 
				 @click="goBack"
				 class="page-link"
				 href="#">
					Previous
				</a>
			</li>

			<li 
			 v-for="page in pagesToShow"
			 :key="page"
			 :class="{active: currentPage == page}"
			 class="page-item">
				<a
				 @click='showPage(page)'
				 href="#"
				 class="page-link">
				 {{ page }}
				</a>
			</li>

			<li class="page-item" :class="{disabled: currentPage == pages[pages.length - 1]}">
				<a 
				 @click='goForward'
				 class="page-link" 
				 href="#">
					Next
				</a>
			</li>

		</ul>
	</nav>
</template>

<script>
export default {
	props: {
		pages: {
			type: Array,
			required: true
		},
		currentPage: {
			type: Number,
			required: true
		},
		maxVisibleButtons: {
			type: Number,
			required: true
		}
	},

	computed: {
		startPage() {
			if (this.currentPage <= 2) {
				return 1;
			}

			if (this.currentPage === this.pages.length) { 
				return this.pages.length - this.maxVisibleButtons + 2;
			}

			return this.currentPage - 2;
		},

		endPage() {
			return Math.min(this.startPage + this.maxVisibleButtons - 1, this.pages.length);
		},

		pagesToShow() {
			const range = [];

			for (let i = this.startPage; i <= this.endPage; i += 1) {
				range.push(i);
			}

			return range;
		},
	},

	methods: {
		goBack() {
			if (this.currentPage != 1) {
				this.$emit("pageChange", this.currentPage - 1);
			}
		},

		showPage(page) {
			if (this.currentPage != page) {
				this.$emit("pageChange", page);
			}
		},

		goForward() {
			if (this.currentPage != this.pages[this.pages.length - 1]) {
				this.$emit("pageChange", this.currentPage + 1);
			}
		}

	}
	
}
</script>