<template>
	<form-create with-helpdesk>
		<template v-slot:default="{ combos: { users }, record, store }">
			<v-card-text>
				<v-autocomplete
					:items="users"
					:return-object="false"
					label="Pengguna"
					v-model="record.user_id"
					@update:search="searchUser($event, store)"
					clearable
				></v-autocomplete>
			</v-card-text>
		</template>
	</form-create>
</template>

<script>
import debounce from "debounce";

export default {
	name: "system-role-create",

	methods: {
		searchUser: debounce(function (val, store) {
			if (!val) {
				return;
			}

			this.$http(`system/api/user/search`, {
				method: "GET",
				params: {
					search: val.trim(),
				},
			}).then((results) => {
				store.combos.users = results;
			});
		}, 300),
	},
};
</script>
