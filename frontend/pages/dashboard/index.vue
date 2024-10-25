<template>
	<page-home page-name="system-dashboard">
		<template v-slot:default>
			<v-row>
				<v-col cols="6">
					<widget-number
						label="Pengguna"
						icon="people"
						value="10.5k"
					></widget-number>
				</v-col>

				<v-col cols="6">
					<widget-number
						label="Operator PD"
						icon="admin_panel_settings"
						value="300"
					></widget-number>
				</v-col>
			</v-row>
		</template>
	</page-home>
</template>

<script>
export default {
	name: "system-dashboard",
};
</script>
