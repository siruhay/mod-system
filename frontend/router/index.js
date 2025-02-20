export default {
	path: "/system",
	meta: { requiredAuth: true },
	component: () =>
		import(
			/* webpackChunkName: "system" */ "@modules/system/frontend/pages/Base.vue"
		),
	children: [
		{
			path: "",
			redirect: {
				name: "system-dashboard",
			},
		},

		{
			path: "dashboard",
			name: "system-dashboard",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/dashboard/index.vue"
				),
		},

		// module
		{
			path: "module",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-module",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-module-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module/crud/create.vue"
						),
				},

				{
					path: ":module/edit",
					name: "system-module-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module/crud/edit.vue"
						),
				},

				{
					path: ":module/show",
					name: "system-module-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module/crud/show.vue"
						),
				},
			],
		},

		// module-ability
		{
			path: "module/:module/ability",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-ability",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-ability-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability/crud/create.vue"
						),
				},

				{
					path: ":ability/edit",
					name: "system-ability-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability/crud/edit.vue"
						),
				},

				{
					path: ":ability/show",
					name: "system-ability-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability/crud/show.vue"
						),
				},
			],
		},

		// module-ability-license
		{
			path: "module/:module/ability/:ability/license",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-license/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-license",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-license/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-license-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-license/crud/create.vue"
						),
				},

				{
					path: ":license/edit",
					name: "system-license-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-license/crud/edit.vue"
						),
				},

				{
					path: ":license/show",
					name: "system-license-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-license/crud/show.vue"
						),
				},
			],
		},

		// module-ability-page
		{
			path: "module/:module/ability/:ability/page",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-page/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-abilitypage",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-page/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-abilitypage-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-page/crud/create.vue"
						),
				},

				{
					path: ":abilitypage/edit",
					name: "system-abilitypage-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-page/crud/edit.vue"
						),
				},

				{
					path: ":abilitypage/show",
					name: "system-abilitypage-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-ability-page/crud/show.vue"
						),
				},
			],
		},

		// module-page
		{
			path: "module/:module/page",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-page",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-page-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page/crud/create.vue"
						),
				},

				{
					path: ":page/edit",
					name: "system-page-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page/crud/edit.vue"
						),
				},

				{
					path: ":page/show",
					name: "system-page-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page/crud/show.vue"
						),
				},
			],
		},

		// module-permission
		{
			path: "module/:module/page/:page/permission",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page-permission/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-permission",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page-permission/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-permission-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page-permission/crud/create.vue"
						),
				},

				{
					path: ":permission/edit",
					name: "system-permission-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page-permission/crud/edit.vue"
						),
				},

				{
					path: ":permission/show",
					name: "system-permission-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/module-page-permission/crud/show.vue"
						),
				},
			],
		},

		// role
		{
			path: "role",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/role/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-role",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/role/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-role-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/role/crud/create.vue"
						),
				},

				{
					path: ":role/edit",
					name: "system-role-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/role/crud/edit.vue"
						),
				},

				{
					path: ":role/show",
					name: "system-role-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/role/crud/show.vue"
						),
				},
			],
		},

		// thirdparty
		{
			path: "thirdparty",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/thirdparty/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-thirdparty",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/thirdparty/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "system-thirdparty-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/thirdparty/crud/create.vue"
						),
				},

				{
					path: ":thirdparty/edit",
					name: "system-thirdparty-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/thirdparty/crud/edit.vue"
						),
				},

				{
					path: ":thirdparty/show",
					name: "system-thirdparty-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/thirdparty/crud/show.vue"
						),
				},
			],
		},

		// report
		{
			path: "report",
			name: "system-report",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/report/index.vue"
				),
		},

		// user
		{
			path: "user",
			component: () =>
				import(
					/* webpackChunkName: "system" */ "@modules/system/frontend/pages/user/index.vue"
				),
			children: [
				{
					path: "",
					name: "system-user",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/user/crud/data.vue"
						),
				},

				{
					path: "/create",
					name: "system-user-create",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/user/crud/create.vue"
						),
				},

				{
					path: ":user/edit",
					name: "system-user-edit",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/user/crud/edit.vue"
						),
				},

				{
					path: ":user/show",
					name: "system-user-show",
					component: () =>
						import(
							/* webpackChunkName: "system" */ "@modules/system/frontend/pages/user/crud/show.vue"
						),
				},
			],
		},
	],
};
