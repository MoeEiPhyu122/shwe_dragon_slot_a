@extends('admin_layouts.app')
@section('styles')
    <style>
        .transparent-btn {
            background: none;
            border: none;
            padding: 0;
            outline: none;
            cursor: pointer;
            box-shadow: none;
            appearance: none;
            /* For some browsers */
        }

        .active-button {
            background-color: #e91e63;
            /* or any color you prefer */
            color: white;
            /* optional: change text color if needed */
        }

        #search {
            margin-top: 40px;
        }

        #product {
            background-color: #CCDDEB;
            color: #e91e63;
        }

        #clear {
            margin-top: 40px;
        }

        th,
        td {
            border: 1px solid !important;
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2">Username</th>
                    <th rowspan="2">Valid Amount</th>
                    <th colspan="3" class="text-center">{{ $child_user_type->name }}</th>
                    <th colspan="3" class="text-center">{{ $parent_user_type->name }}<br> </th>
                </tr>
                <tr>
                    <td>Win/Lose</td>
                    <td>Com</td>
                    <td>Total</td>
                    <td>Win/Lose</td>
                    <td>Com</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->user->user_name }}</td>
                        <td>{{ $report->turnover / 100 }}</td>
                        <td>{{ $report->win_lose / 100 }}</td>
                        <td>{{ $report->commission / 100 }}</td>
                        <td>{{ ($report->turnover + $report->commission) / 100 }}</td>
                        <td>{{ $report->win_lose / 100 }}</td>
                        <td>{{ $report->commission / 100 }}</td>
                        <td>{{ ($report->turnover + $report->parent_commission) / 100 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    {{-- <script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
      searchable: true,
      fixedHeight: true
    });
  </script> --}}
    <script>
        if (document.getElementById('users-search')) {
            const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
                searchable: true,
                fixedHeight: false,
                perPage: 7
            });

            document.querySelectorAll(".export").forEach(function(el) {
                el.addEventListener("click", function(e) {
                    var type = el.dataset.type;

                    var data = {
                        type: type,
                        filename: "material-" + type,
                    };

                    if (type === "csv") {
                        data.columnDelimiter = "|";
                    }

                    dataTableSearch.export(data);
                });
            });
        };
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection