  @if (!empty($mergedData) && count($mergedData) > 0)
      <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped">
              <thead>
                  <tr>
                      <th>@lang('Date')</th>
                      <th>@lang('Order ID')</th>
                      <th>@lang('Previous Due')</th>
                      <th>@lang('Challan Amount')</th>
                      <th>@lang('Return Amount')</th>
                      <th>@lang('Net Amount')</th>
                      <th>@lang('Commission')</th>
                      <th>@lang('Return Commission')</th>
                      <th>@lang('Grand Total')</th>
                      <th>@lang('Paid Amount')</th>
                      <th>@lang('Due Total')</th>
                      <th>@lang('Commission Status')</th>
                      <th>@lang('Due Collection')</th>
                      <th>@lang('Total Due Amount')</th>
                  </tr>
              </thead>
              <tbody>

                  @php
                      $totalnetchallanamount = 0;
                      $totaldueamount = 0;
                      $returncommissiontotal = 0;
                      $continueDue = 0;
                      $isFirstOrder = true;
                  @endphp

                  @foreach ($mergedData as $key => $item)
                      @if ($item['type'] == 'order')
                          @php
                              $order = $item['data'];

                              // প্রথম order থেকে previous_due দিয়ে শুরু
                              if ($isFirstOrder) {
                                  $continueDue = $order->previous_due;
                                  $isFirstOrder = false;
                              }

                              // order related totals
                              $totalnetchallanamount += $order->sub_total - $order->return_amount;
                              $totaldueamount +=
                                  $order->sub_total - $order->return_amount - $order->paid_amount - $order->commission;
                              $returncommissiontotal += $order->orderreturn->sum('commission');

                              // current order due = grand total - paid_amount
                              $currentOrderDue = $order->grand_total - $order->paid_amount;

                              // running continue due
                              $continueDue += $currentOrderDue;
                          @endphp

                          <tr>
                              <td>{{ en2bn(Date('d-m-Y', strtotime($order->date))) }}</td>
                              <td>
                                 <a href="{{ route('admin.order.show', $order->id) }}"> #{{ $order->oid }} </a>
                                  <input type="hidden" name="order_id[]" value="{{ $order->id }}">
                              </td>
                              <td>{{ en2bn(number_format($order->previous_due, 2, '.', ',')) }}
                              </td>
                              <td>{{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}</td>
                              <td>{{ en2bn(number_format($order->return_amount, 2, '.', ',')) }}
                              </td>
                              <td>{{ en2bn(number_format($order->net_amount, 2, '.', ',')) }}
                              </td>
                              <td>{{ en2bn(number_format($order->commission ?? 0, 2, '.', ',')) }}
                              </td>
                              <td>{{ en2bn(number_format($order->orderreturn->sum('commission'), 2, '.', ',')) }}
                              </td>
                              <td>{{ en2bn(number_format($order->grand_total, 2, '.', ',')) }}
                              </td>
                               <td>{{ en2bn(number_format($order->paid_amount, 2, '.', ',')) }}</td>
                              <td>{{ en2bn(number_format($order->grand_total - $order->paid_amount, 2, '.', ',')) }}
                              </td>
                             
                              <td>{{ $order->commission_status }}</td>
                              <td></td>

                              <td class="fw-bold text-danger">
                                  {{ en2bn(number_format($continueDue, 2, '.', ',')) }}</td>
                          </tr>
                      @else
                          @php
                              $payment = $item['data'];

                              // payment দিলে continue due থেকে minus
                              $continueDue -= $payment->amount;
                          @endphp

                          <tr class="table-info">
                              <td>{{ en2bn(Date('d-m-Y', strtotime($payment->date))) }}</td>
                              <td colspan="4">
                                  <span class="badge bg-success">@lang('Due Payment')</span>
                              </td>
                              <td colspan="5">@lang('Customer Due Payment')</td>
                              <td colspan="2"> {{ $payment->created_at }} </td>
                              <td><strong>{{ en2bn(number_format($payment->amount, 2, '.', ',')) }}</strong>
                              </td>

                              <td class="fw-bold text-danger">
                                  {{ en2bn(number_format($continueDue, 2, '.', ',')) }}</td>
                          </tr>
                      @endif
                  @endforeach

              </tbody>
              <tfoot>
                  <tr class="bg-secondary text-white">
                      <td>@lang('Total')</td>
                      <td></td>
                      <td></td>
                      <td>{{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}</td>
                      <td>{{ en2bn(number_format($orders->sum('return_amount'), 2, '.', ',')) }}
                      </td>
                      <input type="hidden" name="return_amount" value="{{ $orders->sum('return_amount') }}">
                      <input type="hidden" name="net_amount" value="{{ $orders->sum('net_amount') }}">
                      <input type="hidden" name="paid_amount" value="{{ $orders->sum('paid_amount') }}">
                      <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                      </td>
                      <td>{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                      </td>
                      <td>{{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                      <td>{{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                      </td>
                      <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                      </td>
                      <td>{{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                      <td></td>
                      <td>{{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }}
                      </td>
                      <td></td>

                  </tr>
              </tfoot>
          </table>
      </div>
  @endif
