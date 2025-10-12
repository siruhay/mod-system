<template>
	<user-apps page-name="account-service" @click:impersonateLeave="impersonateLeave">
		<template v-slot:default="{ modules }">
			<page-paper user-avatar>
				<template v-if="modules.personal && modules.personal.length > 0">
					<page-divider label="personal" uppercase></page-divider>

					<v-card-text>
						<v-row justify="center" no-gutters>
							<v-col
								cols="3"
								md="2"
								v-for="(module, index) in modules.personal"
								:key="index"
							>
								<widget-apps
									:color="module.color"
									:highlight="module.highlight"
									:icon="module.icon"
									:label="module.name"
									@click="openModule(module)"
								></widget-apps>
							</v-col>
						</v-row>
					</v-card-text>
				</template>

				<template
					v-if="modules.administrator && modules.administrator.length > 0"
				>
					<page-divider label="administrator" uppercase></page-divider>

					<v-card-text>
						<v-row justify="center" no-gutters>
							<v-col
								cols="3"
								md="2"
								v-for="(module, index) in modules.administrator"
								:key="index"
							>
								<widget-apps
									:color="module.color"
									:highlight="module.highlight"
									:icon="module.icon"
									:label="module.name"
									@click="openModule(module)"
								></widget-apps>
							</v-col>
						</v-row>
					</v-card-text>
				</template>
			</page-paper>
		</template>
	</user-apps>
</template>

<script>
export default {
	name: "account-apps",

	methods: {
		impersonateLeave: function ({ mapUserModule }) {
			this.$http(`account/api/impersonate-leave`, {
				method: "POST",
			}).then((response) => {
				mapUserModule(response);
			});
		},

		openModule: function (module) {
			this.$router.push({ name: module.slug + "-dashboard" });
		},
	},
};
</script>
