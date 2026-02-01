<div class="row mt-4">
	<div class="col-12">
		<h4 class="text-center mb-0 p-0 mt-5">Trial Balance</h4>
		<p class="">@lang('Date'):
			@if (isset(request()->start_date))
				{{ en2bn(Date('d-m-Y', strtotime(request()->start_date))) }}
			@endif
			-
			@if (isset(request()->end_date))
				{{ en2bn(Date('d-m-Y', strtotime(request()->end_date))) }}
			@endif
		</p>

		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th rowspan="2">@lang('SL')</th>
					<th rowspan="2">@lang('Name')</th>
					<th rowspan="2">@lang('No Financial')</th>
					<th colspan="2" class="text-center">@lang('Financial')</th>
				</tr>
				<tr>
					<th>@lang('Debit')</th>
					<th>@lang('Credit')</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;">Sales Order Modules
					</th>
				</tr>
				<tr>
					<td>{{ en2bn('1') }}</td>
					<td style="text-align: left">@lang('Opening Cash Balance')</td>
					<td></td>
					<td></td>
					<td>{{ en2bn(number_format($availablebalance, 2, '.', ',')) }} </td>
				</tr>
				<tr>
					<td>{{ en2bn('2') }}</td>
					<td style="text-align: left">@lang('Total Sales')</td>
					<td>{{ en2bn(number_format($totalsales, 0, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('3') }}</td>
					<td style="text-align: left">@lang('Orders Amount')</td>
					<td>{{ en2bn(number_format($salesamount, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('4') }}</td>
					<td style="text-align: left">@lang('Return Orders Amount')</td>
					<td>{{ en2bn(number_format($returnamounts, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('5') }}</td>
					<td style="text-align: left">@lang('Commission Amount')</td>
					<td>{{ en2bn(number_format($ordercommissionamount, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('6') }}</td>
					<td style="text-align: left">@lang('Unpaid Commission Amount')</td>
					<td>{{ en2bn(number_format($unpaidcommissionamount, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('7') }}</td>
					<td style="text-align: left">@lang('Order Payments')</td>
					<td></td>
					<td></td>
					<td>{{ en2bn(number_format($salepayments, 2, '.', ',')) }}</td>
				</tr>

				<tr>
					<td>{{ en2bn('8') }}</td>
					<td style="text-align: left">@lang('Customer Due Receive Amount')</td>
					<td></td>
					<td></td>
					<td>{{ en2bn(number_format($customerduepayment, 2, '.', ',')) }}</td>
				</tr>
				<tr>
					<td>{{ en2bn('9') }}</td>
					<td style="text-align: left">@lang('Commission Invoice Payment')</td>
					<td></td>
					<td>{{ en2bn(number_format($commissioninvoicepayment, 2, '.', ',')) }}</td>
					<td></td>

				</tr>
				<tr>
					<td>{{ en2bn('10') }}</td>
					<td style="text-align: left">@lang('Marketer Invoice Payment')</td>
					<td></td>
					<td>{{ en2bn(number_format($marketercommissionpayment, 2, '.', ',')) }}</td>
					<td></td>
				</tr>

				<tr>
					<td>{{ en2bn('11') }}</td>
					<td style="text-align: left">@lang('Customer Receiable Amount')</td>
					<td>{{ en2bn(number_format($totalreceivableamount, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;">Item Purchase
						Modules</th>
				</tr>
				<tr>
					<td>{{ en2bn('12') }}</td>
					<td style="text-align: left">@lang('Supplier Opending Due')</td>
					<td>{{ en2bn(number_format($opendingsupplierdue, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('13') }}</td>
					<td style="text-align: left">@lang('Total Item Order')</td>
					<td>{{ en2bn(number_format($totalitemorder, 0, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('14') }}</td>
					<td style="text-align: left">@lang('Item Order Amount')</td>
					<td>{{ en2bn(number_format($itemorderamount, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('15') }}</td>
					<td style="text-align: left">@lang('Item Return Amount')</td>
					<td>{{ en2bn(number_format($itemreturns, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('16') }}</td>
					<td style="text-align: left">@lang('Item Payment Amount')</td>
					<td></td>
					<td>{{ en2bn(number_format($itempayments, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('17') }}</td>
					<td style="text-align: left">@lang('Supplier Due Payment Amount')</td>
					<td></td>
					<td>{{ en2bn(number_format($supplierduepaymnet, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('18') }}</td>
					<td style="text-align: left">@lang('Supplier Payable Amount')</td>
					<td>{{ en2bn(number_format($totalpayableamount, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;">HR Modules</th>
				</tr>
				<tr>
					<td>{{ en2bn('19') }}</td>
					<td style="text-align: left">@lang('Total Salary Amount')</td>
					<td>{{ en2bn(number_format($totalsalary, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('20') }}</td>
					<td style="text-align: left">@lang('Salary Advance')</td>
					<td></td>
					<td>{{ en2bn(number_format($salaryadvance, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('21') }}</td>
					<td style="text-align: left">@lang('Salary Payment')</td>
					<td></td>
					<td>{{ en2bn(number_format($salarypayment, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('22') }}</td>
					<td style="text-align: left">@lang('Payable Salary')</td>
					<td>{{ en2bn(number_format($payablesalary, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('23') }}</td>
					<td style="text-align: left">@lang('Over Time Allowance')</td>
					<td></td>
					<td>{{ en2bn(number_format($overtimeallowance, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('24') }}</td>
					<td style="text-align: left">@lang('Employee Loan')</td>
					<td></td>
					<td>{{ en2bn(number_format($loans, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('25') }}</td>
					<td style="text-align: left">@lang('Receivable Loan amount')</td>
					<td>{{ en2bn(number_format($total_loan_due, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;">Expenses Modules
					</th>
				</tr>
				<tr>
					<td>{{ en2bn('26') }}</td>
					<td style="text-align: left">@lang('Expenses Amount')</td>
					<td>{{ en2bn(number_format($expense, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>

				</tr>
				<tr>
					<td>{{ en2bn('27') }}</td>
					<td style="text-align: left">@lang('Expense Payment Amount')</td>
					<td></td>
					<td>{{ en2bn(number_format($expensepayments, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('28') }}</td>
					<td style="text-align: left">@lang('Asset Expenses Payment')</td>
					<td></td>
					<td>{{ en2bn(number_format($assetexpensepayment, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('29') }}</td>
					<td style="text-align: left">@lang('Monthly Expenses Payment')</td>
					<td></td>
					<td>{{ en2bn(number_format($monthlyexpensepayment, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('30') }}</td>
					<td style="text-align: left">@lang('Transport Expense Payments')</td>
					<td></td>
					<td>{{ en2bn(number_format($transportexpensepayment, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;">Admin Modules</th>
				</tr>
				<tr>
					<td>{{ en2bn('31') }}</td>
					<td style="text-align: left">@lang('Official Loan')</td>
					<td></td>
					<td></td>
					<td>{{ en2bn(number_format($officeloans, 2, '.', ',')) }}</td>
				</tr>
				<tr>
					<td>{{ en2bn('32') }}</td>
					<td style="text-align: left">@lang('Official Loan Payment')</td>
					<td></td>
					<td>{{ en2bn(number_format($officialloanpayment, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('33') }}</td>
					<td style="text-align: left">@lang('Payable Official Loan')</td>
					<td>{{ en2bn(number_format($payableofficeloan, 2, '.', ',')) }}</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('34') }}</td>
					<td style="text-align: left">@lang('Deposit Amount')</td>
					<td></td>
					<td></td>
					<td>{{ en2bn(number_format($deposit, 2, '.', ',')) }}</td>
				</tr>
				<tr>
					<td>{{ en2bn('35') }}</td>
					<td style="text-align: left">@lang('Withdrawal Amount')</td>
					<td></td>
					<td>{{ en2bn(number_format($withdrawal, 2, '.', ',')) }}</td>
					<td></td>
				</tr>
				<tr>

				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;"> Stocks Value
					</th>
				</tr>
				<tr>
					<td>{{ en2bn('36') }}</td>
					<td style="text-align: left">@lang('Products Stock Value')</td>
					<td> {{ en2bn(number_format($productstockvalue, 2, '.', ',')) }}
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>{{ en2bn('36') }}</td>
					<td style="text-align: left">@lang('Item Stock Value')</td>
					<td> {{ en2bn(number_format($itemstockvalue, 2, '.', ',')) }}
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<th colspan="5" style="text-align:center;background-color: #ddd;">Asset Value
					</th>
				</tr>
				<tr>
					<td>{{ en2bn('38') }}</td>
					<td style="text-align: left">@lang('Assets')</td>
					<td> {{ en2bn(number_format($assets, 2, '.', ',')) }}
					</td>
					<td></td>
					<td></td>
				</tr>

			</tbody>
			<tfoot>
				<tr>
					<th colspan="3">@lang('Total Amount')</th>
					<th>{{ en2bn(number_format($totalexpenditure, 2, '.', ',')) }} </th>
					<th>{{ en2bn(number_format($availablebalance + $totalincome, 2, '.', ',')) }} </th>
				</tr>
				<tr>
					<th colspan="3" style="text-align: left">@lang('Cash Balance')</th>
					<th colspan="2">{{ en2bn(number_format($availablebalance + $totalincome - $totalexpenditure), 2, '.', ',') }}
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
