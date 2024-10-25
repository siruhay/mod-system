<template>
	<form-edit>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-text-field v-model="record.name"></v-text-field>
			</v-card-text>
		</template>
	</form-edit>
</template>

<script>
export default {
	name: "system-user-edit",
};
</script>
