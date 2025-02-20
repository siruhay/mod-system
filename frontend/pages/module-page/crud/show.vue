<template>
	<form-show with-helpdesk with-activity-logs>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row>
					<v-col cols="12">
						<v-text-field
							label="Parent"
							v-model="
								record.parent_id
							"
							hide-details
							readonly
						></v-text-field>
					</v-col>

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
							label="Slug"
							v-model="record.slug"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Title"
							v-model="record.title"
							hide-details
							readonly
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
						<v-text-field
							label="Path"
							v-model="record.path"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Side"
							v-model="record.side"
							hide-details
							inset
							readonly
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Dock"
							v-model="record.dock"
							hide-details
							inset
							readonly
						></v-switch>
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
						name: 'system-permission',
					})
				"
				>BUKA PERMISSION</v-btn
			>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-module-show",
};
</script>
