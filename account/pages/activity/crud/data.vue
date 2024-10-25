<template>
	<form-data disable-create>
		<template v-slot:info="{ theme }">
			<div class="text-overline mt-6">Filters</div>
			<v-divider></v-divider>

			<table
				class="w-100"
				style="border-spacing: 0px"
			>
				<colgroup>
					<col width="48px" />
					<col width="auto" />
				</colgroup>

				<tbody>
					<tr>
						<td style="padding-top: 16px; vertical-align: baseline">
							<v-avatar
								size="32"
								:color="`${theme}-lighten-1`"
							>
								<v-icon size="small">text_fields</v-icon>
							</v-avatar>
						</td>

						<td style="padding-top: 16px">
							<span class="text-caption font-weight-medium"
								>Bulan</span
							>
							<span
								class="d-block text-caption text-grey-darken-1"
								>Filter ini berfungsi untuk menampilkan data
								berdasarkan bulan update.</span
							>
						</td>
					</tr>
				</tbody>
			</table>
		</template>

		<template v-slot:mobile="{ record, showDelete, theme }">
			<item-data
				:show-delete="showDelete"
				:value="record"
			>
				<template v-slot:title>
					<div class="d-flex">
						{{ record.modelName }}

						<v-spacer></v-spacer>

						<v-chip
							:color="`${theme}-lighten-5`"
							:class="`text-${theme}`"
							size="x-small"
							variant="flat"
							>{{ record.event }}</v-chip
						>
					</div>
				</template>
			</item-data>
		</template>
	</form-data>
</template>

<script>
export default {
	name: "account-news-data",
};
</script>
