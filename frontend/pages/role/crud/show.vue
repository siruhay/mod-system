<template>
	<form-show with-helpdesk with-activity-logs>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="7">
						<v-text-field
							label="Nama"
							v-model="record.name"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="5">
						<v-text-field
							label="Slug"
							v-model="record.slug"
							hide-details
						></v-text-field>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-role-show",
};
</script>
