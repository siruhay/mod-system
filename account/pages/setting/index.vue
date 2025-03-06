<template>
	<page-blank
		page-name="account-setting"
		page-key="setting"
		title="setting"
		show-sidenav
	>
		<template v-slot:default="{ combos: { colors }, record, theme, store }">
			<page-paper>
				<div class="d-flex align-center my-4">
					<v-chip
						:color="`${theme}`"
						class="mx-auto"
						size="small"
						variant="flat"
					>
						<div
							class="font-weight-medium text-caption text-uppercase"
						>
							{{ tabTitle }}
						</div>
					</v-chip>
				</div>

				<v-tabs
					align-tabs="center"
					color="primary"
					v-model="tabSelected"
					stacked
				>
					<v-tab value="account">
						<v-icon icon="person"></v-icon>
					</v-tab>

					<v-tab value="password">
						<v-icon icon="lock"></v-icon>
					</v-tab>

					<v-tab value="twofactor">
						<v-icon icon="encrypted"></v-icon>
					</v-tab>

					<v-tab value="sessions">
						<v-icon icon="settings_cinematic_blur"></v-icon>
					</v-tab>
				</v-tabs>

				<v-divider></v-divider>

				<v-tabs-window v-model="tabSelected">
					<v-tabs-window-item value="account">
						<v-sheet class="mx-auto" max-width="400">
							<v-card-text>
								<v-row>
									<v-col cols="12">
										<v-text-field
											label="Nama Pengguna"
											v-model="record.username"
											hide-details
										></v-text-field>
									</v-col>

									<v-col cols="12">
										<v-select
											:items="colors"
											label="Thema"
											v-model="record.theme"
											hide-details
											@update:modelValue="
												updateTheme($event, store)
											"
										></v-select>
									</v-col>

									<v-col cols="12">
										<v-select
											:items="colors"
											label="Highlight"
											v-model="record.highlight"
											@update:modelValue="
												updateHighlight($event, store)
											"
											hide-details
										></v-select>
									</v-col>
								</v-row>
							</v-card-text>

							<v-divider></v-divider>

							<v-card-text class="d-flex">
								<v-spacer></v-spacer>

								<v-btn
									:color="theme"
									variant="flat"
									@click="updateProfile(record)"
									>update profile</v-btn
								>
							</v-card-text>
						</v-sheet>
					</v-tabs-window-item>

					<v-tabs-window-item value="password">
						<v-sheet class="mx-auto" max-width="400">
							<v-card-text>
								<v-row>
									<v-col cols="12">
										<v-text-field
											:type="show1 ? 'text' : 'password'"
											:append-inner-icon="
												show1
													? 'visibility'
													: 'visibility_off'
											"
											label="Saat ini"
											v-model="record.current_password"
											hide-details
											@click:append-inner="show1 = !show1"
										></v-text-field>
									</v-col>

									<v-col cols="12">
										<v-text-field
											:type="show2 ? 'text' : 'password'"
											:append-inner-icon="
												show2
													? 'visibility'
													: 'visibility_off'
											"
											label="Sandi Baru"
											v-model="record.password"
											hide-details
											@click:append-inner="show2 = !show2"
										></v-text-field>
									</v-col>

									<v-col cols="12">
										<v-text-field
											:type="show3 ? 'text' : 'password'"
											:append-inner-icon="
												show3
													? 'visibility'
													: 'visibility_off'
											"
											label="Konfirmasi"
											v-model="
												record.password_confirmation
											"
											hide-details
											@click:append-inner="show3 = !show3"
										></v-text-field>
									</v-col>
								</v-row>
							</v-card-text>

							<v-divider></v-divider>

							<v-card-text class="d-flex">
								<v-spacer></v-spacer>
								<v-btn
									:color="theme"
									variant="flat"
									@click="updatePassword(record)"
									>update katasandi</v-btn
								>
							</v-card-text>
						</v-sheet>
					</v-tabs-window-item>

					<v-tabs-window-item value="twofactor">
						<v-sheet class="mx-auto" max-width="400">
							<v-card-text>
								<div
									v-if="
										!record.enabled_two_factor &&
										!record.confirmed_two_factor
									"
									class="text-grey-darken-1"
								>
									<p class="text-justify">
										Anda belum mengaktifkan fitur
										otentifikasi dua langkah
									</p>

									<p class="mt-3 text-justify">
										Saat fitur otentifikasi dua langkah di
										aktifkan, Anda akan di minta untuk scan
										kode QR dan meng-input token keamanan.
										Anda akan mendapatkan token ini melalui
										aplikasi Google Authenticator di
										handphone.
									</p>
								</div>

								<div
									v-if="
										record.enabled_two_factor &&
										!record.confirmed_two_factor
									"
									class="text-grey-darken-1"
								>
									<p class="text-justify">
										Silahkan scan QR code di bawah
										menggunakan aplikasi Google
										Authenticator di handphone Anda.
									</p>

									<div
										class="d-flex align-center justify-center py-4"
										v-html="record.svg_two_factor"
									></div>

									<p class="text-justify">
										Silahkan masukan token dari aplikasi
										Google Authenticator untuk konfirmasi
										aktivasi fitur ini.
									</p>

									<div class="text-overline text-center">
										token konfirmasi
									</div>

									<v-otp-input
										label="Konfirmasi"
										length="6"
										v-model="record.otp_two_factor"
									></v-otp-input>
								</div>

								<div
									v-if="
										record.enabled_two_factor &&
										record.confirmed_two_factor
									"
									class="text-grey-darken-1"
								>
									<p>
										Fitur otentifikasi dua langkah sekarang
										telah aktif.
									</p>

									<p class="mt-3 text-justify">
										Simpan kode pemulihan dibawah ini pada
										tempat yang aman. Kode ini dapat di
										gunakan untuk memulihkan kembali akses
										dua langkah otentifikasi akun Anda jika
										perangkat hilang.
									</p>

									<div
										class="d-flex mt-4 justify-center bg-yellow-lighten-5 py-4 rounded-lg"
									>
										<code class="text-center">
											<p
												v-for="(
													code, index
												) in record.recovery_code_two_factor"
												:key="index"
											>
												{{ code }}
											</p>
										</code>
									</div>
								</div>
							</v-card-text>

							<v-divider></v-divider>

							<v-card-text class="d-flex">
								<v-spacer></v-spacer>

								<v-btn
									v-if="
										record.enabled_two_factor &&
										!record.confirmed_two_factor
									"
									:color="theme"
									variant="flat"
									@click="
										confirmTwoFactorAuthentication(record)
									"
									>konfirmasi</v-btn
								>

								<v-btn
									v-if="record.enabled_two_factor"
									color="deep-orange"
									class="ml-2"
									variant="flat"
									@click="
										disableTwoFactorAuthentication(record)
									"
									>hapus</v-btn
								>

								<v-btn
									v-if="!record.enabled_two_factor"
									:color="theme"
									variant="flat"
									@click="
										enableTwoFactorAuthentication(record)
									"
									>Aktifkan Fitur</v-btn
								>
							</v-card-text>
						</v-sheet>
					</v-tabs-window-item>

					<v-tabs-window-item value="sessions">
						<v-sheet class="mx-auto" max-width="400">
							<v-card-text>
								<div class="text-grey-darken-1 mb-4">
									<p>
										Jika diperlukan, Anda dapat keluar dari
										semua sesi di semua device yang pernah
										terhubung.
									</p>

									<p class="mt-3">
										list dibawah ini adalah beberapa sesi
										terbaru Anda. Daftar ini mungkin tidak
										lengkap, jika Anda merasa bahwa akun
										Anda telah disusupi. Anda juga harus
										meng-update Katasandi.
									</p>
								</div>

								<div
									v-for="(session, index) in record.sessions"
									:key="index"
									:class="{ 'mt-2': index > 0 }"
									class="d-block text-grey-darken-1"
								>
									<div class="d-flex align-center w-100 pb-2">
										<v-icon v-if="session.agent.is_mobile"
											>phone_iphone</v-icon
										>
										<v-icon
											v-else-if="session.agent.is_tablet"
											>tablet_mac</v-icon
										>
										<v-icon v-else>computer</v-icon>

										<div class="d-block flex-grow-1 mx-2">
											<div
												class="text-caption font-weight-bold"
											>
												{{ session.agent.platform }} -
												{{ session.agent.browser }}
											</div>
											<div
												class="text-caption"
												style="
													font-size: 10px !important;
												"
											>
												IP: {{ session.ip_address }},
												<span
													class="text-green"
													v-if="
														session.is_current_device
													"
													>This device</span
												>
												<span v-else
													>Last activity:
													{{
														session.last_active
													}}</span
												>
											</div>
										</div>

										<v-btn
											:color="theme"
											density="compact"
											variant="flat"
											rounded="lg"
											icon
										>
											<v-icon size="small">map</v-icon>
										</v-btn>
									</div>

									<v-divider></v-divider>
								</div>
							</v-card-text>

							<v-card-text class="d-flex pt-1">
								<v-spacer></v-spacer>

								<v-btn :color="theme" variant="flat">
									Hapus Sesi Lainnya

									<v-dialog
										activator="parent"
										max-width="340"
									>
										<template v-slot:default="{ isActive }">
											<v-card
												prepend-icon="delete"
												title="Hapus Sesi Lainnya?"
											>
												<v-card-text>
													<p
														class="text-caption text-justify mb-4"
													>
														Untuk menghapus sesi
														browser yang lain,
														silahkan masukan
														katasandi Anda pada
														input di bawah ini.
													</p>

													<v-row>
														<v-col cols="12">
															<v-text-field
																:append-inner-icon="
																	visible
																		? 'visibility'
																		: 'visibility_off'
																"
																:type="
																	visible
																		? 'text'
																		: 'password'
																"
																label="Katasandi"
																placeholder="Masukan Katasandi"
																v-model="
																	record.password
																"
																hide-details
																@click:append-inner="
																	visible =
																		!visible
																"
															></v-text-field>
														</v-col>
													</v-row>
												</v-card-text>

												<div class="d-flex px-6 pb-6">
													<v-spacer></v-spacer>

													<v-btn
														:color="theme"
														text="Batal"
														@click="
															isActive.value = false
														"
													></v-btn>

													<v-btn
														color="deep-orange"
														class="ml-2"
														text="Hapus"
														variant="flat"
														@click="
															logoutOtherDevices(
																() =>
																	(isActive.value = false),
																record
															)
														"
													></v-btn>
												</div>
											</v-card>
										</template>
									</v-dialog>
								</v-btn>
							</v-card-text>
						</v-sheet>
					</v-tabs-window-item>
				</v-tabs-window>
			</page-paper>
		</template>
	</page-blank>
</template>

<script>
export default {
	name: "account-setting",

	computed: {
		tabTitle: function () {
			switch (this.tabSelected) {
				case "password":
					return "Katasandi";

				case "twofactor":
					return "Dua Faktor Otentikasi";

				case "sessions":
					return "Sesi Browser";

				default:
					return "Pengguna";
			}
		},
	},

	data: () => ({
		tabSelected: "account",
		svgQrCode: null,
		visible: false,
		show1: false,
		show2: false,
		show3: false,
	}),

	methods: {
		confirmTwoFactorAuthentication: function (record) {
			this.$http(`account/api/setting/confirmed-factor-authentication`, {
				method: "POST",
				params: {
					code: record.otp_two_factor,
				},
			})
				.then(() => {
					record.confirmed_two_factor = true;
				})
				.catch(() => {
					record.otp_two_factor = null;
				});
		},

		disableTwoFactorAuthentication: function (record) {
			this.$http(`account/api/setting/two-factor-authentication`, {
				method: "DELETE",
			}).then(() => {
				record.svg_two_factor = null;
				record.enabled_two_factor = false;
				record.confirmed_two_factor = false;
				record.url_two_factor = null;
			});
		},

		enableTwoFactorAuthentication: function (record) {
			this.$http(`account/api/setting/two-factor-authentication`, {
				method: "POST",
			}).then(({ recovery, svg, url }) => {
				record.svg_two_factor = svg;
				record.enabled_two_factor = true;
				record.recovery_code_two_factor = recovery;
				record.url_two_factor = url;
			});
		},

		logoutOtherDevices: function (callback, record) {
			if (typeof callback === "function") {
				callback();
			}

			this.$http(`account/api/setting/logout-other-devices`, {
				method: "POST",
				params: {
					password: record.password,
				},
			}).then(({ sessions }) => {
				record.sessions = sessions;
			});
		},

		updateHighlight: function (highlight, store) {
			store.highlight = highlight;
		},

		updatePassword: function (record) {
			this.$http(`account/api/setting/update-password`, {
				method: "POST",
				params: {
					current_password: record.current_password,
					password: record.password,
					password_confirmation: record.password_confirmation,
				},
			});
		},

		updateProfile: function (record) {
			this.$http(`account/api/setting/update-profile`, {
				method: "POST",
				params: record,
			}).then(({ auth }) => {
				this.auth = auth;
				this.$storage.setItem("auth", auth);
				this.theme = "theme" in this.auth ? this.auth.theme : "teal";
			});
		},

		updateTheme: function (theme, store) {
			store.theme = theme;
		},
	},
};
</script>
