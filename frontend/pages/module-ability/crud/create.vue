<template>
	<form-create with-helpdesk>
		<template v-slot:default="{ combos: { modules, roles }, record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-combobox
							:items="modules"
							:return-object="false"
							label="Module"
							v-model="record.module_id"
							readonly
						></v-combobox>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="roles"
							:return-object="false"
							label="Role"
							v-model="record.role_id"
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:feed="{ theme }">
			<span
				>Form ini berfungsi untuk menambahkan data ability dari sebuah
				module.</span
			>

			<help-list>
				<help-list-item
					:theme="theme"
					icon="text_fields"
					title="Module"
					subtitle="Combo Pilihan module."
				></help-list-item>

				<help-list-item
					:theme="theme"
					icon="text_fields"
					title="Role"
					subtitle="Combo Pilihan role."
				></help-list-item>
			</help-list>
		</template>
	</form-create>
</template>

<script>
export default {
	name: "system-module-create",
};
</script>
