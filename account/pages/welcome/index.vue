<template>
    <page-welcome>
        <template
            v-slot:default="{ authenticate, challenge, finduser, resetpass }"
        >
            <v-sheet color="transparent" v-if="forgotPassword">
                <v-row>
                    <v-col cols="12" sm="6">
                        <div class="text-h4 font-weight-light">
                            Temukan akun Anda
                        </div>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-card-text class="pa-0">
                            <v-row v-if="!resetFeature">
                                <v-col cols="12">
                                    <v-text-field
                                        label="Pengguna"
                                        placeholder="Masukan NIP/NIK"
                                        hide-details
                                        v-model="email"
                                    ></v-text-field>
                                </v-col>
                            </v-row>

                            <v-row v-if="resetFeature">
                                <v-col cols="12">
                                    <v-text-field
                                        :append-inner-icon="
                                            visible
                                                ? 'visibility'
                                                : 'visibility_off'
                                        "
                                        :type="visible ? 'text' : 'password'"
                                        label="Sandi Baru"
                                        placeholder="Masukan Katasandi"
                                        v-model="password"
                                        hide-details
                                        @click:append-inner="visible = !visible"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-text-field
                                        :append-inner-icon="
                                            visible
                                                ? 'visibility'
                                                : 'visibility_off'
                                        "
                                        :type="visible ? 'text' : 'password'"
                                        label="Konfirmasi"
                                        placeholder="Masukan Katasandi"
                                        v-model="password_confirmation"
                                        hide-details
                                        @click:append-inner="visible = !visible"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <v-card-text class="pa-0 pt-6">
                            <v-row dense>
                                <v-col cols="6">
                                    <v-btn
                                        block
                                        color="primary"
                                        size="large"
                                        variant="text"
                                        rounded="pill"
                                        @click="
                                            forgotPassword = !forgotPassword;
                                            resetFeature = false;
                                        "
                                        >Batal</v-btn
                                    >
                                </v-col>

                                <v-col cols="6">
                                    <v-btn
                                        v-if="!resetFeature"
                                        block
                                        color="primary"
                                        size="large"
                                        rounded="pill"
                                        variant="flat"
                                        @click="finduser"
                                        >Cari</v-btn
                                    >

                                    <v-btn
                                        v-else
                                        block
                                        color="primary"
                                        size="large"
                                    >
                                        Reset

                                        <v-dialog
                                            activator="parent"
                                            max-width="340"
                                        >
                                            <template
                                                v-slot:default="{ isActive }"
                                            >
                                                <v-sheet>
                                                    <v-toolbar>
                                                        <v-toolbar-title
                                                            >OTP
                                                            Token</v-toolbar-title
                                                        >
                                                    </v-toolbar>

                                                    <v-card-text class="pb-2">
                                                        <p
                                                            class="text-justify px-2 pt-1 pb-2"
                                                        >
                                                            Silahkan masukan
                                                            token dari aplikasi
                                                            Authenticator untuk
                                                            dapat melakukan
                                                            perubahan sandi.
                                                        </p>

                                                        <v-otp-input
                                                            label="OTP"
                                                            length="6"
                                                            v-model="
                                                                twoFactorCode
                                                            "
                                                            hide-details
                                                        ></v-otp-input>
                                                    </v-card-text>

                                                    <v-divider></v-divider>

                                                    <div
                                                        class="d-flex px-6 py-4"
                                                    >
                                                        <v-spacer></v-spacer>

                                                        <v-btn
                                                            class="ml-auto"
                                                            text="Batal"
                                                            @click="
                                                                isActive.value = false
                                                            "
                                                        ></v-btn>

                                                        <v-btn
                                                            class="ml-2"
                                                            text="Kirim"
                                                            @click="
                                                                resetpass(
                                                                    () =>
                                                                        (isActive.value = false)
                                                                )
                                                            "
                                                        ></v-btn>
                                                    </div>
                                                </v-sheet>
                                            </template>
                                        </v-dialog>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-col>
                </v-row>
            </v-sheet>

            <v-sheet color="transparent" v-else>
                <v-row>
                    <v-col cols="12" sm="6">
                        <div
                            class="text-h4 font-weight-light"
                            v-if="!twoFactor"
                        >
                            Otentikasi Pengguna
                        </div>

                        <div class="text-h4 font-weight-light" v-else>
                            Otentikasi OTP
                        </div>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <v-card-text class="pa-0">
                            <v-row v-if="!twoFactor">
                                <v-col cols="12">
                                    <v-text-field
                                        label="Pengguna"
                                        placeholder="Masukan NIP/NIK"
                                        hide-details
                                        v-model="email"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-text-field
                                        :append-inner-icon="
                                            visible
                                                ? 'visibility'
                                                : 'visibility_off'
                                        "
                                        :type="visible ? 'text' : 'password'"
                                        label="Katasandi"
                                        placeholder="Masukan Katasandi"
                                        v-model="password"
                                        hide-details
                                        @click:append-inner="visible = !visible"
                                    ></v-text-field>
                                </v-col>
                            </v-row>

                            <v-row dense v-else>
                                <v-col cols="12" v-if="twoModeCode">
                                    <v-otp-input
                                        label="OTP"
                                        length="6"
                                        v-model="twoFactorCode"
                                        hide-details
                                    ></v-otp-input>
                                </v-col>

                                <v-col cols="12" v-else>
                                    <v-text-field
                                        class="my-3"
                                        label="Kode Pemulihan"
                                        hide-details
                                        v-model="twoFactorCode"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-switch
                                        v-model="twoModeCode"
                                        :label="
                                            twoModeCode
                                                ? `Mode Pemulihan`
                                                : `Mode Token`
                                        "
                                        hide-details
                                        inset
                                    ></v-switch>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <v-card-text class="pa-0">
                            <div
                                class="text-blue font-weight-medium text-center my-4 cursor-pointer"
                                @click="forgotPassword = !forgotPassword"
                            >
                                Lupa Password?
                            </div>

                            <v-btn
                                v-if="!twoFactor"
                                block
                                color="primary"
                                size="large"
                                rounded="pill"
                                variant="flat"
                                @click="authenticate"
                                >Masuk</v-btn
                            >

                            <v-row dense v-else>
                                <v-col cols="4">
                                    <v-btn
                                        block
                                        color="primary"
                                        size="large"
                                        @click="twoFactor = !twoFactor"
                                        >Batal</v-btn
                                    >
                                </v-col>

                                <v-col cols="8">
                                    <v-btn
                                        block
                                        color="primary"
                                        size="large"
                                        @click="challenge"
                                        >Konfirmasi</v-btn
                                    >
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-col>
                </v-row>
            </v-sheet>
        </template>
    </page-welcome>
</template>

<script>
import { usePageStore } from "@pinia/pageStore";
import { storeToRefs } from "pinia";

export default {
    name: "welcome-page",

    setup() {
        const store = usePageStore();

        const {
            email,
            forgotPassword,
            password,
            password_confirmation,
            resetFeature,
            twoFactor,
            twoFactorCode,
            twoFactorMode,
            twoModeCode,
            theme,
        } = storeToRefs(store);

        return {
            email,
            forgotPassword,
            password,
            password_confirmation,
            resetFeature,
            twoFactor,
            twoFactorCode,
            twoFactorMode,
            twoModeCode,
            theme,
        };
    },

    data: () => ({
        visible: false,
    }),
};
</script>
