<template>
	<div>
		<div class="container">
			<div class="row">
				<div class="col-12 text-center mb-5">
					<h1>Popular PHP Repositories</h1>
				</div>

				<div
				 v-for='repository in displayedRepositories'
				 :key='repository.id'
				 class='col-xl-4 col-md-6 col-12 my-3'
				>
					<repository
					 :name="repository.name"
					 :id="repository.id"
					 :description="repository.description"
					/>

				</div>
					
			</div>
			<div class="row justify-content-end">
				<div class="col-auto">
					<pagination
					 :pages="pages"
					 :currentPage="currentPage"
					 :maxVisibleButtons="maxVisibleButtons"
					 @pageChange="onPageChange"
					/>
				</div>
			</div>
		</div>
	</div>
</template> 

<script>
import Pagination from "./Pagination.vue";
import Repository from "./Repository.vue";

export default {
	components: {
		Pagination,
		Repository
	},

	data: function() {
		return {
			repositories: [],
			displayedRepositories: [],
			pages: [],
			currentPage: 1,
			maxPerPage: 6,
			maxVisibleButtons: 5
		}
	},

	mounted: function() {
		this.getRepositories();
	},

	watch: {
		currentPage: function() {
			this.displayedRepositories = this.paginateRepositories();
		}
	},

	methods: {
		getRepositories() {
			const that = this;
			window.axios.get('/api/getRepositories').then((response) => {
				console.log(response.data);
				that.repositories = response.data;

				that.setPages();
			});
		},

		setPages() {
			let numberOfPages = Math.ceil(this.repositories.length / this.maxPerPage);
			for (let index = 1; index <= numberOfPages; index++) {
				this.pages.push(index);
			}
			this.displayedRepositories = this.paginateRepositories();
		},

		paginateRepositories() {
			let to = (this.currentPage * this.maxPerPage);
			let from = to - this.maxPerPage;
			return this.repositories.slice(from, to);
		},

		onPageChange(page) {
      		this.currentPage = page;
		}
	}

}
</script>