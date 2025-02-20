<template>
	<form-show
		hide-edit
		hide-delete
		with-helpdesk
	>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Nama"
							v-model="record.name"
							hide-details
						></v-text-field>
					</v-col>
				</v-row>

				<div
					class="text-overline mt-4 mb-2"
				>
					ACCESS TOKEN
				</div>

				<v-sheet
					class="d-flex align-center justify-center pa-4"
					color="cyan-lighten-5"
					rounded="lg"
				>
					<div class="text-button">
						{{ record.token }}
					</div>
				</v-sheet>
			</v-card-text>
		</template>

		<template v-slot:info="{ record, theme }">
			<div class="text-overline mt-4">
				Aksi
			</div>
			<v-divider></v-divider>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				@click="generateToken(record)"
				>GENERATE TOKEN</v-btn
			>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-thirdparty-show",

	methods: {
		generateToken(record) {
			this.$http(
				`system/api/thirdparty/${record.id}/generate-token`
			).then((response) => {
				record.token = response.token;
			});
		},
	},
};
</script>
