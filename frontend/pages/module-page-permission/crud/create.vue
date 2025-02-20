<template>
	<form-create with-helpdesk>
		<template
			v-slot:default="{
				combos: { permissions },
				record,
			}"
		>
			<v-card-text>
				<v-select
					:items="permissions"
					label="Permission"
					v-model="record.name"
					hide-details
				></v-select>
			</v-card-text>
		</template>
	</form-create>
</template>

<script>
export default {
	name: "system-role-create",
};
</script>
