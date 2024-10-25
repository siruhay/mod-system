<template>
    <form-create with-helpdesk>
        <template v-slot:default="{ record }">
            <v-card-text>
                <v-text-field
                    label="Nama"
                    v-model="record.name"
                    hide-details
                ></v-text-field>
            </v-card-text>
        </template>
    </form-create>
</template>

<script>
export default {
    name: "system-operator-create",
};
</script>
