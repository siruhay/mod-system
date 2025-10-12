<template>
	<user-home page-name="account-dashboard">
		<page-paper user-avatar>
			<v-card-text>
				<v-row dense>
					<v-col cols="6">
						<widget-card>
							<div class="text-overline">pelatihan ku</div>
						</widget-card>
					</v-col>

					<v-col cols="6">
						<widget-card>
							<div class="text-overline">posyandu ku</div>
						</widget-card>
					</v-col>

					<v-col cols="12">
						<widget-card>
							<apexchart
								type="bar"
								:options="{
									chart: {
										toolbar: {
											show: false,
										},
									},

									plotOptions: {
										bar: {
											borderRadius: 4,
											dataLabels: {
												position: 'top', // top, center, bottom
											},
										},
									},

									legend: { show: false },

									xaxis: {
										labels: {
											show: false,
										},
									},

									yaxis: {
										labels: {
											show: false,
										},
									},
								}"
								:series="[
									{
										name: 'Inflation',
										data: [2.3, 3.1, 4.0, 10.1, 4.0],
									},
								]"
							></apexchart>
						</widget-card>
					</v-col>

					<v-col cols="6">
						<widget-number
							label="Total Ukuran"
							icon="inventory_2"
							value="100 GB"
						></widget-number>
					</v-col>

					<v-col cols="6">
						<widget-number
							label="Total Ukuran"
							icon="inventory_2"
							value="100 GB"
						></widget-number>
					</v-col>
				</v-row>
			</v-card-text>
		</page-paper>
	</user-home>
</template>

<script>
export default {
	name: "account-dashboard",
};
</script>
