<style>
    table.dataTable.cell-border thead th {
        border: 1px solid rgba(0, 0, 0, 0.25);
        text-align: center;
    }

    table.dataTable.cell-border tbody th,
    table.dataTable.cell-border tbody td {
        border-top: 1px solid rgba(0, 0, 0, 0.15);
        border-right: 1px solid rgba(0, 0, 0, 0.15);
        border-bottom: 1px solid rgba(0, 0, 0, 0.15);
        padding: 5px 10px 5px 10px;
    }

    table.dataTable.cell-border tbody tr th:first-child,
    table.dataTable.cell-border tbody tr td:first-child {
        border-left: 1px solid rgba(0, 0, 0, 0.15);
    }

    table.dataTable.cell-border tbody tr:first-child th,
    table.dataTable.cell-border tbody tr:first-child td {
        border-top: none;
    }

    table.dataTable thead th,
    tavle.dataTable tbody td {
        max-width: 100px;
    }

    @if (Auth::user()->role == '1')

        table.dataTable tbody td:nth-child(2),
        table.dataTable tbody td:nth-child(5),
        table.dataTable tbody td:nth-child(8),
        table.dataTable tbody td:nth-child(11),
        table.dataTable tbody td:nth-child(13),
        table.dataTable tbody td:nth-child(15),
        table.dataTable tbody td:nth-child(17) {
            min-width: 100px;
        }
    @endif
</style>
