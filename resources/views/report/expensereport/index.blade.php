@extends('admin.layouts.app', ['title' => 'Expense Reports'])
@section('panel')

@include('report.layouts.default',
    ['title' => 'Expense Reports', 'url' => 'admin.reports.expensereport', [
            'range_date' => $range_date ? $range_date : null,
        ]
    ])
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
