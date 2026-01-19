@extends('admin.layouts.app', ['title' => 'Customer & Supplier Reports'])
@section('panel')

@include('report.layouts.default',
    ['title' => 'Customer & Supplier Reports', 'url' => 'admin.reports.customersupplier', [
            'range_date' => $range_date ? $range_date : null,
        ]
    ])

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.customersupplier') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type" class="form-label">Type:</label>
                                    <select name="type" id="type" class="form-control type">
                                        <option {{ request('type') == 'all' ? 'selected' : '' }} value="all">All</option>
                                        <option {{ request('type') == 'customer' ? 'selected' : '' }} value="customer">Customer</option>
                                        <option {{ request('type') == 'supplier' ? 'selected' : '' }} value="supplier">Supplier</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="contact" class="form-label">Contact:</label>
                                    <select name="contact" id="contact" class="form-control contact">
                                        <option value="all">All</option>
                                        @foreach ($users as $user)
                                        <option {{ request('contact') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mt-2 form-group">
                                    <button type="submit" class="mt-4 btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Contact</th>
                                <th>Total Purcheses</th>
                                <th>Total Purcheses Return</th>
                                <th>Total Sale</th>
                                <th>Total Sale Return</th>
                                <th>Opening Due Balance</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_contacts as $contact)
                            <tr>
                                <td>{{ $contact['name'] }}</td>
                                <td>{{ number_format($contact['total_purchase'], 3) }}</td>
                                <td>{{ number_format($contact['total_sale'], 3) }}</td>
                                <td>{{ number_format($contact['total_purchase_return'], 3) }}</td>
                                <td>{{ number_format($contact['total_sale_return'], 3) }}</td>
                                <td>{{ number_format($contact['opening_balance'], 3) }}</td>
                                <td>{{ number_format($contact['total_due'], 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script')

<script>
    $(document).ready(function() {
        $('.type').on('change', function() {
            $type = $(this).val();
            $url = $(this).closest('form').attr('action');

            if ($type == 'customer') {
                $url = $url + "?type=customer";
            } else if ($type == 'supplier') {
                $url = $url + "?type=supplier";
            } else {
                $url = $url + "?type=all";
            }

            $(this).closest('form').attr('action', $url);
        });


        $('.contact').on('change', function() {
            $contact = $(this).val();
            $url = $(this).closest('form').attr('action');

            $url = $url +"?contact=" + $contact;

            $(this).closest('form').attr('action', $url);
        });
    });
</script>


@endpush


@push('style')
<style>

    .select2-container--default .select2-selection--single {
        border-radius: .375rem !important;
        height: 42px !important;
    }

    .no-focus:focus {
        outline: none;
    }

    .no-border {
        border: none;
    }

    table tr td p {
        font-size: 10px !important;
    }

    p {
        font-size: 11px !important;
    }
</style>
@endpush
