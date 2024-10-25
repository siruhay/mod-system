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
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Type"
							v-model="record.type"
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Domain"
							v-model="record.domain"
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Prefix"
							v-model="record.prefix"
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-text-field
							label="Icon"
							v-model="record.icon"
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-textarea
							label="Repositori"
							rows="2"
							v-model="record.repository"
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

		<template v-slot:info="{ theme }">
			<div class="text-overline mt-4">Aksi</div>
			<v-divider></v-divider>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				>CHECK FOR UPDATE</v-btn
			>

			<div class="text-overline mt-4">Link</div>
			<v-divider></v-divider>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				>BUKA ABILITY</v-btn
			>

			<v-btn
				class="mt-3"
				:color="theme"
				block
				variant="flat"
				>BUKA PAGE</v-btn
			>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-auditor-show",
};
</script>
