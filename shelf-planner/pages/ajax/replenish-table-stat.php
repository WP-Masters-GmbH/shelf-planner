<?php

	$categories    = sp_get_categories();
	$products_data = sp_get_products_data_home( implode( ',', array_keys( $categories ) ), array());

	$current_forecast_units = 0;
	$current_forecast_value = 0;
	foreach($products_data as $item_product) {
		$current_forecast_units += $item_product[$weeks];
		$current_forecast_value += $item_product['cost_price'] * $item_product[$weeks];
	}

	$products_data_last_year = sp_get_products_data_home_last_year( implode( ',', array_keys( $categories ) ), array(), $last_weeks);

	$last_year_forecast_units = 0;
	$last_year_forecast_value = 0;
	foreach($products_data_last_year as $item_product) {
		$last_year_forecast_units += $item_product['this_week'];
		$last_year_forecast_value += $item_product['cost_price'] * $item_product['this_week'];
	}

	$percent_compare_units = 0;
	$percent_compare_value = 0;
	if($last_year_forecast_units > 0) {
		$percent_compare_units = $last_year_forecast_units > $current_forecast_units ? ($last_year_forecast_units / $current_forecast_units) * 100 : ($current_forecast_units / $last_year_forecast_units) * 100;
		$percent_compare_units = round($percent_compare_units);
	}
	if($last_year_forecast_value > 0) {
		$percent_compare_value = $last_year_forecast_value > $current_forecast_value ? ($last_year_forecast_value / $current_forecast_value) * 100 : ($last_year_forecast_value / $current_forecast_value) * 100;
		$percent_compare_value = round($percent_compare_value);
	}
?>

<table class="card-tab-second-table" id="replenish-table-stat">
	<tr>
		<td class="forecast-tab">
			Forecast Units
		</td>
		<td class="forecast-numbers">
			<?php echo esc_html($current_forecast_units); ?>
			<span class="numbers-grey">Previous Year:<br><?php echo esc_html($last_year_forecast_units); ?></span>
		</td>
		<td class="card-tab-percent <?php if($last_year_forecast_units > $current_forecast_units && $last_year_forecast_units != 0) { echo esc_attr('lower'); } elseif($last_year_forecast_units < $current_forecast_units && $last_year_forecast_units != 0) { echo esc_attr('higher'); } else { echo esc_attr('zero'); } ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="8.539" height="8.539" viewBox="0 0 8.539 8.539">
				<path d="M436.93,55.229l-1.349,1.349v-6.22l-6.229,6.229-.96-.96L434.62,49.4H428.4l1.349-1.349h7.181Z" transform="translate(-428.391 -48.048)" fill="#00b050"/>
			</svg>
			<span><?php echo esc_html($percent_compare_units); ?> %</span>
		</td>
	</tr>
	<tr class="tab-second-row">
		<td class="forecast-tab">
			Forecast Value
		</td>
		<td class="forecast-numbers">
			<?php echo esc_html(get_woocommerce_currency_symbol().$current_forecast_value); ?>
			<span class="numbers-grey">Previous Year:<br><?php echo esc_html(get_woocommerce_currency_symbol().$last_year_forecast_value); ?></span>
		</td>
		<td class="card-tab-percent <?php if($last_year_forecast_value > $current_forecast_value && $last_year_forecast_value != 0) { echo esc_attr('lower'); } elseif($last_year_forecast_value < $current_forecast_value && $last_year_forecast_value != 0) { echo esc_attr('higher'); } else { echo esc_attr('zero'); } ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="8.539" height="8.539" viewBox="0 0 8.539 8.539">
				<path d="M436.93,55.229l-1.349,1.349v-6.22l-6.229,6.229-.96-.96L434.62,49.4H428.4l1.349-1.349h7.181Z" transform="translate(-428.391 -48.048)" fill="#00b050"/>
			</svg>
			<span><?php echo esc_html($percent_compare_value); ?> %</span>
		</td>
	</tr>
</table>