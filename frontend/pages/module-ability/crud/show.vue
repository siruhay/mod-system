<template>
	<form-show with-helpdesk with-activity-logs>
		<template
			v-slot:default="{
				combos: { modules, roles },
				record,
			}"
		>
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
						<v-combobox
							:items="modules"
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
							label="Role"
							v-model="
								record.role_id
							"
							hide-details
							readonly
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:info="{ theme }">
			<div class="text-overline mt-4">
				Link
			</div>
			<v-divider></v-divider>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				@click="
					$router.push({
						name: 'system-license',
					})
				"
				>BUKA USER</v-btn
			>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				@click="
					$router.push({
						name: 'system-abilitypage',
					})
				"
				>BUKA PAGE</v-btn
			>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-module-show",
};
</script>
