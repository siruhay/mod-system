<template>
	<form-show
		with-helpdesk
		with-activity-logs
	>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Name"
							v-model="record.name"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Type"
							v-model="record.type"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Domain"
							v-model="record.domain"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Prefix"
							v-model="record.prefix"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Color"
							v-model="record.color"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Highlight"
							v-model="record.highlight"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Icon"
							v-model="record.icon"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-textarea
							label="Repositori"
							rows="2"
							v-model="record.git_address"
							hide-details
							readonly
						></v-textarea>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Desktop"
							v-model="record.desktop"
							hide-details
							inset
							readonly
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Mobile"
							v-model="record.mobile"
							hide-details
							inset
							readonly
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Enabled"
							v-model="record.enabled"
							hide-details
							inset
							readonly
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Published"
							v-model="record.published"
							hide-details
							inset
							readonly
						></v-switch>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:info="{ record, theme }">
			<div class="text-overline mt-4">Aksi</div>
			<v-divider></v-divider>

			<v-card-text
				:class="`bg-${theme}-lighten-5 rounded-lg mt-3`"
				v-if="updateChecked"
			>
				<p v-if="updateStatus"><strong>Update Is Available</strong></p>
				<p v-else>
					<strong>module {{ record.name }} is up to date.</strong>
				</p>

				<div class="mt-2">
					<p class="text-caption">
						update version: {{ record.updated_version }}
					</p>
					<p class="text-caption">
						current version: {{ record.current_version }}
					</p>
					<p class="text-caption">
						updated notes: {{ record.updated_notes }}
					</p>
				</div>
			</v-card-text>

			<v-row dense>
				<template v-if="updateChecked && updateStatus">
					<v-col cols="6">
						<v-btn
							class="mt-3"
							:color="`${theme}-lighten-4`"
							block
							variant="flat"
							@click="cancelUpdate"
							>Cancel</v-btn
						>
					</v-col>

					<v-col cols="6">
						<v-btn
							class="mt-3"
							:color="theme"
							block
							variant="flat"
							@click="processUpdate(record)"
							>Update</v-btn
						>
					</v-col>
				</template>

				<v-col
					cols="12"
					v-else
				>
					<v-btn
						class="mt-3"
						:color="theme"
						:loading="updateLoading"
						block
						variant="flat"
						@click="checkForUpdate(record)"
						>CHECK FOR UPDATE</v-btn
					>
				</v-col>
			</v-row>

			<div class="text-overline mt-4">Link</div>
			<v-divider></v-divider>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				@click="$router.push({ name: 'system-page' })"
				>BUKA PAGE</v-btn
			>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				@click="$router.push({ name: 'system-ability' })"
				>BUKA ABILITY</v-btn
			>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-module-show",

	data: () => ({
		updateLoading: false,
		updateStatus: false,
		updateChecked: false,
	}),

	methods: {
		checkForUpdate: function (record) {
			this.updateLoading = true;

			this.$http(`system/api/module/${record.id}/check-for-update`)
				.then((response) => {
					record.current_version = response.current_version;
					record.updated_version = response.updated_version;
					record.updated_notes = response.updated_notes;

					this.updateStatus = response.status;
					this.updateLoading = false;
					this.updateChecked = true;
				})
				.catch(() => {
					this.updateLoading = false;
				});
		},

		cancelUpdate: function () {
			this.updateChecked = false;
			this.updateStatus = false;
		},

		processUpdate: function (record) {
			this.$http(`system/api/module/${record.id}/process-update`)
				.then(() => {
					//
				})
				.catch(() => {
					//
				});
		},
	},
};
</script>
