<template>
	<form-show
		with-helpdesk
		with-activity-logs
	>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-text-field
					label="Name"
					v-model="record.name"
					hide-details
				></v-text-field>
			</v-card-text>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "system-role-show",
};
</script>
