export default [
	{
		path: "/",
		meta: { requiredAuth: false },
		name: "welcome-page",
		component: () =>
			import(
				/* webpackChunkName: "welcome" */ "@modules/system/account/pages/welcome"
			),
	},

	{
		path: "/account",
		meta: { requiredAuth: true },
		component: () =>
			import(
				/* webpackChunkName: "welcome" */ "@modules/system/account/pages/Base.vue"
			),
		children: [
			{
				path: "",
				redirect: { name: "account-dashboard" },
			},

			// dashboard
			{
				path: "dashboard",
				name: "account-dashboard",
				component: () =>
					import(
						/* webpackChunkName: "account" */ "@modules/system/account/pages/dashboard/index.vue"
					),
			},

			// service
			{
				path: "service",
				name: "account-service",
				component: () =>
					import(
						/* webpackChunkName: "account" */ "@modules/system/account/pages/service/index.vue"
					),
			},

			// activity
			{
				path: "activity",
				component: () =>
					import(
						/* webpackChunkName: "account" */ "@modules/system/account/pages/activity/index.vue"
					),

				children: [
					{
						path: "",
						name: "account-activity",
						component: () =>
							import(
								/* webpackChunkName: "account" */ "@modules/system/account/pages/activity/crud/data.vue"
							),
					},
				],
			},

			// notification
			{
				path: "notification",
				component: () =>
					import(
						/* webpackChunkName: "account" */ "@modules/system/account/pages/notification/index.vue"
					),
				children: [
					{
						path: "",
						name: "account-notification",
						component: () =>
							import(
								/* webpackChunkName: "account" */ "@modules/system/account/pages/notification/crud/data.vue"
							),
					},
				],
			},

			// setting
			{
				path: "setting",
				name: "account-setting",
				component: () =>
					import(
						/* webpackChunkName: "account" */ "@modules/system/account/pages/setting/index.vue"
					),
			},
		],
	},
];
