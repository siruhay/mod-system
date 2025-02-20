<template>
	<form-create with-helpdesk>
		<template
			v-slot:default="{
				combos: { pages },
				record,
			}"
		>
			<v-card-text>
				<v-select
					:items="pages"
					label="Available Page"
					v-model="record.page_id"
				></v-select>
			</v-card-text>

			<div class="py-0">
				<v-divider>
					<v-chip
						:color="`${theme}`"
						size="small"
						variant="flat"
					>
						<div
							class="font-weight-medium text-lowercase"
						>
							permissions
						</div>
					</v-chip>
				</v-divider>
			</div>

			<v-card-text>
				<table
					class="v-plain-table with-border-bottom w-100"
					style="border-spacing: 0px"
				>
					<colgroup>
						<col width="48px" />
						<col width="70px" />
						<col width="auto" />
					</colgroup>

					<tbody>
						<tr
							v-for="(
								permission, index
							) in record.permissions"
							:key="index"
						>
							<td>
								<v-checkbox-btn
									:model-value="
										permission.value
									"
									readonly
								></v-checkbox-btn>
							</td>

							<td
								class="text-caption font-weight-bold"
							>
								{{
									permission.name
								}}
							</td>

							<td
								class="text-caption"
							>
								{{
									permission.slug
								}}
							</td>
						</tr>
					</tbody>
				</table>
			</v-card-text>
		</template>
	</form-create>
</template>

<script>
export default {
	name: "system-role-create",
};
</script>
