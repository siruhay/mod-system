<template>
	<form-edit with-helpdesk>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Name"
							v-model="record.name"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="['administrator', 'personal']"
							label="Type"
							v-model="record.type"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Domain"
							v-model="record.domain"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Prefix"
							v-model="record.prefix"
							hide-details
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
							append-inner-icon="public"
							label="Icon"
							v-model="record.icon"
							hide-details
							@click:append-inner="openGoogleIcon"
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-textarea
							label="Repositori"
							rows="2"
							v-model="record.git_address"
							hide-details
						></v-textarea>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Desktop"
							v-model="record.desktop"
							hide-details
							inset
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Mobile"
							v-model="record.mobile"
							hide-details
							inset
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Enabled"
							v-model="record.enabled"
							hide-details
							inset
						></v-switch>
					</v-col>

					<v-col cols="6">
						<v-switch
							label="Published"
							v-model="record.published"
							hide-details
							inset
						></v-switch>
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
