<template>
	<div>
		<div class="toast-container position-absolute p-3">
			<div class="toast" id='toast'>
				<div class="toast-header">
					<strong class="me-auto">Error</strong>
				</div>
				<div class="toast-body">
					{{ errorMessage }}
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-12 text-center mb-5">
					<h1>Popular PHP Repositories</h1>
				</div>
			</div>

			<div class="row d-flex justify-content-end">
				<div class="col-auto">
					<a href="#!" 
					 class="button"
					 :class="{disabled: loading}"
					 @click='updateRepositories'>
					 Populate Repositories
					</a>
				</div>
			</div>

			<div class='row' v-show="loading">
				<div class="col-12 text-center">
					<div class="spinner-border" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
				</div>	
			</div>

			<div v-show='!loading && displayedRepositories.length > 0' class='row'>
				<div
				 v-for='repository in displayedRepositories'
				 :key='repository.id'
				 class='col-md-6 col-12 my-2'
				>
					<repository
					 :name="repository.name"
					 :stars="repository.stargazers_count"
					 :id="repository.id"
					 :description="parseNullDescription(repository.description)"
					 :createdAt="repository.created_at"
					 :pushedAt="repository.pushed_at"
					 :url="repository.url"
					/>

				</div>
				
			</div>

			<div v-show='!loading && displayedRepositories.length == 0' class='row'>
				<div class="col-12 text-center my-5">
					<p>No repositories stored. Click "Populate Repositories" button above to pull from GitHub, store in database, and show.</p>
				</div>
			</div>

			<div class="row justify-content-end" v-show='!loading'>
				<div class="col-auto mt-3">
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
			loading: true,
			repositories: [],
			displayedRepositories: [],
			pages: [],
			currentPage: 1,
			maxPerPage: 10,
			maxVisibleButtons: 5,
			errorMessage: ""
		}
	},

	mounted: function() {
		this.getRepositories();
		// Initialize toast 
		let toast = new window.bootstrap.Toast(document.getElementById('toast'));
	},

	watch: {
		// When currentPage changes, it will attempt to paginate the results. 
		currentPage: function() {
			this.displayedRepositories = this.paginateRepositories();
		}
	},

	methods: {

		// On component mounted and when updateRepositories is called
		getRepositories() {
			const that = this;
			
			window.axios.get('/api/getRepositories').then(response => {
				that.repositories = response.data;
				that.setPages();

			}).catch(error => {
				const toastElem = document.getElementById('toast');
				const toast = window.bootstrap.Toast.getInstance(toastElem);
			
				that.errorMessage = error.response.data;

				toast.show();
				that.loading = false;
			});
		},

		// On populate button click
		updateRepositories() {
			const that = this;
			that.loading = true;

			window.axios.post('/api/updateRepositories').then(response => {
				that.repositories = [];
				that.displayedRepositories = [];
				that.pages = [];
				that.currentPage = 1;

				that.getRepositories();

			}).catch(error => {
				const toastElem = document.getElementById('toast');
				const toast = window.bootstrap.Toast.getInstance(toastElem);

				that.errorMessage = "GitHub API responsed with " + error.response.data;
				if (error.response.data == 403) {
					that.errorMessage = "GitHub API limit reached (10 requests/minute) - showing existing repositories."
				}

				toast.show();
				that.loading = false;
			});
		},

		// After repositories have been grabbed, divide into 'pages' and paginate
		setPages() {
			let numberOfPages = Math.ceil(this.repositories.length / this.maxPerPage);
			for (let index = 1; index <= numberOfPages; index++) {
				this.pages.push(index);
			}
			this.displayedRepositories = this.paginateRepositories();
			this.loading = false;
		},

		// Returns the slice of what should be showing on x page (currentPage watch)
		paginateRepositories() {
			let to = (this.currentPage * this.maxPerPage);
			let from = to - this.maxPerPage;
			return this.repositories.length > 0 ? this.repositories.slice(from, to) : [];
		},

		// Pagination component emits event that calls this
		onPageChange(page) {
      		this.currentPage = page;
		},

		// In case a null description comes back
		parseNullDescription(description) {
			return description ?? '(no description provided)';
		}
	}

}
</script>