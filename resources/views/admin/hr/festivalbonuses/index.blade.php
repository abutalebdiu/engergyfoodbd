@extends('admin.layouts.app', ['title' => 'Festival Bonus List'])
@section('panel')
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Festival Bonus List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.festivalbonus.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> Add New Festival Bonus</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Bonus Eligible Date</th>
                            <th>Year</th>
                            <th>Note</th>
                            <th>@lang('Entry By')</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($festivalbonuses as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->date }}</td>
                                <td>{{ $item->year }}</td>
                                <td>{{ $item->note }}</td>
                                <td>{!! entry_info($item) !!} </td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-3 fs-6">
                                        <a href="{{ route('admin.festivalbonus.edit', $item->id) }}" class="btn btn-primary btn-sm"><i
                                                class="bi bi-pencil-fill"></i> Edit</a>
                                        <a href="{{ route('admin.festivalbonus.show', $item->id) }}" class="btn btn-primary btn-sm"><i
                                                        class="bi bi-gear"></i> Generate</a>
                                        <a href="javascript:;" onclick="deleteItem({{ $item->id }})" class="btn btn-danger btn-sm"><i
                                                class="bi bi-trash-fill"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{ $festivalbonuses->links() }}
        </div>
    </div>
@endsection
