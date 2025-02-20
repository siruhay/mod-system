<template>
	<form-create with-helpdesk>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Parent"
							v-model="
								record.parent_id
							"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Name"
							v-model="record.name"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Slug"
							v-model="record.slug"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Title"
							v-model="record.title"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Icon"
							v-model="record.icon"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Path"
							v-model="record.path"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Side"
							v-model="record.side"
							hide-details
							inset
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Dock"
							v-model="record.dock"
							hide-details
							inset
						></v-switch>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-create>
</template>

<script>
export default {
	name: "system-module-create",
};
</script>
