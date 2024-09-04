<style>
    table.dataTable.cell-border thead th {
        border: 1px solid rgba(0, 0, 0, 0.25);
        text-align: center;
        font-size: 10px;
    }

    table.dataTable.cell-border tbody th,
    table.dataTable.cell-border tfoot td,
    table.dataTable.cell-border thead td,
    table.dataTable.cell-border tbody td {
        border-top: 1px solid rgba(0, 0, 0, 0.15);
        border-right: 1px solid rgba(0, 0, 0, 0.15);
        border-bottom: 1px solid rgba(0, 0, 0, 0.15);
        padding: 2px 5px 2px 5px;
        font-size: 10px;
    }

    table.dataTable.cell-border tbody tr th:first-child,
    table.dataTable.cell-border tfoot tr td:first-child,
    table.dataTable.cell-border thead tr td:first-child,
    table.dataTable.cell-border tbody tr td:first-child {
        border-left: 1px solid rgba(0, 0, 0, 0.15);
    }

    table.dataTable.cell-border tbody tr:first-child th,
    table.dataTable.cell-border tbody tr:first-child td {
        border-top: none;
    }

    table.dataTable thead th,
    tavle.dataTable tbody td {
        max-width: 80px;
    }

    @if (Auth::user()->role == '1')

        table.dataTable tbody td:nth-child(2) {
            min-width: 70px;
        }

        table.dataTable tbody td:nth-child(5),
        table.dataTable tbody td:nth-child(8),
        table.dataTable tbody td:nth-child(9),
        table.dataTable tbody td:nth-child(12),
        table.dataTable tbody td:nth-child(13),
        table.dataTable tbody td:nth-child(15),
        table.dataTable tbody td:nth-child(16),
        table.dataTable tbody td:nth-child(18),
        table.dataTable tbody td:nth-child(19),
        table.dataTable tbody td:nth-child(21) {
            min-width: 80px;
        }

        table.dataTable tbody td:nth-child(22) {
            min-width: 60px;
        }
    @endif
</style>
