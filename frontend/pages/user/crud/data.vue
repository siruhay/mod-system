<template>
	<form-data hide-addnew>
		<template v-slot:info="{ theme }">
			<div class="text-overline mt-4">
				Aksi
			</div>

			<v-divider class="mb-3"></v-divider>

			<v-row dense>
				<v-col cols="12">
					<v-btn
						:color="theme"
						block
						@click="
							grantUserPermission
						"
						>GRANT PERMISSIONS</v-btn
					>
				</v-col>
			</v-row>
		</template>
	</form-data>
</template>

<script>
export default {
	name: "system-user-crete",

	methods: {
		grantUserPermission() {
			this.$http(
				`system/api/user/grant-permissions`,
				{
					method: "POST",
				}
			);
		},
	},
};
</script>
