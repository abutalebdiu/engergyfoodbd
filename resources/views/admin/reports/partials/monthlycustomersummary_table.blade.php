<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>@lang('ID')</th>
			<th>@lang('Customer')</th>
			<th>@lang('Address')</th>
			<th>@lang('Commission Status')</th>
			<th>@lang('Total Orders')</th>
			<th>@lang('Challan Amount')</th>
			<th>@lang('Return Amount')</th>
			<th>@lang('Net Amount')</th>
			<th>@lang('Commission')</th>
			<th>@lang('Return Commission')</th>
			<th>@lang('Grand Total')</th>
			<th>@lang('Paid Amount')</th>
			<th>@lang('Due Collection')</th>
			<th>@lang('মোট আদায়')</th>
			<th>Month Due({{ @$monthname }})</th>
			<th>@lang('Previous Due')</th>
			<th>@lang('Total Due Amount')</th>
		</tr>
	</thead>
	<tbody>
		@php
			$t_total_orders = 0;
			$t_last_month_due = 0;
			$t_order_amount = 0;
			$t_return_amount = 0;
			$t_net_amount = 0;
			$t_commission = 0;
			$t_return_commission = 0;
			$t_grand_total = 0;
			$t_paid_amount = 0;
			$t_due_collection = 0;
			$t_monthy_due = 0;
			$t_total_due_amount = 0;
		@endphp

		@forelse($rows as $row)
			@php
				$t_total_orders += $row['total_orders'];
				$t_last_month_due += $row['last_month_due'];
				$t_order_amount += $row['order_amount'];
				$t_return_amount += $row['return_amount'];
				$t_net_amount += $row['net_amount'];
				$t_commission += $row['commission'];
				$t_return_commission += $row['return_commission'];
				$t_grand_total += $row['grand_total'];
				$t_paid_amount += $row['paid_amount'];
				$t_due_collection += $row['due_collection'];
				$t_total_due_amount += $row['total_due_amount'];
				$t_monthy_due += $row['grand_total'] - $row['paid_amount'] - $row['due_collection'];
			@endphp

			<tr>
				<td>{{ $row['uid'] }}</td>
				<td class="text-start">{{ $row['name'] }}</td>
				<td class="text-start">{{ $row['address'] }}</td>
				<td>{{ $row['commission_type'] }}</td>

				<td class="text-end">{{ en2bn(number_format($row['total_orders'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['order_amount'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['return_amount'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['net_amount'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['commission'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['return_commission'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['grand_total'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['paid_amount'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['due_collection'], 2)) }}</td>
				<td class="text-end">
					{{ en2bn(number_format($row['paid_amount'] + $row['due_collection'], 2)) }}
				</td>

				<td class="text-end">
					{{ en2bn(number_format($row['grand_total'] - $row['paid_amount'] - $row['due_collection'], 2)) }}</td>
				<td class="text-end">{{ en2bn(number_format($row['last_month_due'], 2)) }}</td>
				<td class="text-end text-danger fw-bold">
					{{ en2bn(number_format($row['total_due_amount'], 2)) }}
				</td>
			</tr>

		@empty
			<tr>
				<td colspan="15" class="text-center">No Data Found</td>
			</tr>
		@endforelse

	</tbody>
	<tfoot>
		<tr class="bg-secondary text-white">
			<th colspan="4" class="text-end">@lang('Total')</th>
			<th class="text-end">{{ en2bn(number_format($t_total_orders, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_order_amount, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_return_amount, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_net_amount, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_commission, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_return_commission, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_grand_total, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_paid_amount, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_due_collection, 2)) }}</th>
			<th class="text-end">
				{{ en2bn(number_format($t_paid_amount + $t_due_collection, 2)) }}
			</th>
			<th class="text-end">{{ en2bn(number_format($t_monthy_due, 2)) }}</th>
			<th class="text-end">{{ en2bn(number_format($t_last_month_due, 2)) }}</th>
			<th class="text-end">
				{{ en2bn(number_format($t_total_due_amount, 2)) }}
			</th>
		</tr>
	</tfoot>
</table>
