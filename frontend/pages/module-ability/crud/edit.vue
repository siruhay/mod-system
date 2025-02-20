<template>
	<form-edit with-helpdesk>
		<template
			v-slot:default="{
				combos: { modules, roles },
				record,
			}"
		>
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-combobox
							:items="modules"
							:return-object="false"
							label="Module"
							v-model="
								record.module_id
							"
							hide-details
							readonly
						></v-combobox>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="roles"
							:return-object="false"
							label="Role"
							v-model="
								record.role_id
							"
							hide-details
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-edit>
</template>

<script>
export default {
	name: "system-module-edit",

	methods: {
		openGoogleIcon: function () {
			window.open(
				`https://fonts.google.com/icons?icon.set=Material+Icons`
			);
		},
	},
};
</script>
