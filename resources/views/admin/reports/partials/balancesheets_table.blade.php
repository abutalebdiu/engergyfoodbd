	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th colspan="3">Assets</th>
				<th colspan="3">Liabilities</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td style="text-align:left;padding-left:10px">Office Assets</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($assetsamount, 2)) }}</td>
				<td>1</td>
				<td style="text-align:left;padding-left:10px">Investment Equity</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($liabilities, 2)) }}</td>
			</tr>
			<tr>
				<td>2</td>
				<td style="text-align:left;padding-left:10px">Asset Expenses</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($assetexpenses, 2)) }}</td>
				<td>2</td>
				<td style="text-align:left;padding-left:10px">Office Payable (Salary)</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($salarypayable, 2)) }}</td>
			</tr>
			<tr>
				<td>3</td>
				<td style="text-align:left;padding-left:10px">Salary Advanced</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($salaryadvances, 2)) }}</td>
				<td>3</td>
				<td style="text-align:left;padding-left:10px">Supplier Payable</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($payables, 2)) }}</td>
			</tr>
			<tr>
				<td>4</td>
				<td style="text-align:left;padding-left:10px">Items Stock Value</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($itemstockamount, 2)) }}</td>
				<td>4</td>
				<td style="text-align:left;padding-left:10px"> Monthly Expenses </td>
				<td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($monthlyexpense, 2)) }} </td>
			</tr>
			<tr>
				<td>4</td>
				<td style="text-align:left;padding-left:10px">Products Stock Value</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($prductstockamount, 2)) }}</td>
				<td>4</td>
				<td style="text-align:left;padding-left:10px"> কারখানা ভাড়া </td>
				<td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($factoryrent, 2)) }} </td>
			</tr>
			<tr>
				<td>4</td>
				<td style="text-align:left;padding-left:10px">Employee Loan Receivable</td>
				<td style="text-align: right;padding-right:10px">{{ en2bn(number_format($employeeloan, 2)) }}</td>
				<td>4</td>
				<td style="text-align:left;padding-left:10px"> জামানত </td>
				<td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($jamanot, 2)) }}</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2" style="text-align:left;padding-left:10px">Total Assets</th>
				<th style="text-align: right;padding-right:10px">{{ en2bn(number_format($totalassets, 2)) }}</th>
				<th colspan="2">Total Liabilities</th>
				<th style="text-align: right;padding-right:10px">{{ en2bn(number_format($totalliabilities, 2)) }}</th>
			</tr>
			<tr>
				<th colspan="5"> Different</th>
				<th>{{ en2bn(number_format($differentvalue, 2)) }}</th>
			</tr>
		</tfoot>
	</table>
