<template>
    <form-edit with-helpdesk>
        <template v-slot:default="{ record }">
            <v-card-text>
                <v-text-field
                    label="Name"
                    v-model="record.name"
                    hide-details
                ></v-text-field>
            </v-card-text>
        </template>
    </form-edit>
</template>

<script>
export default {
    name: "system-role-edit",
};
</script>
