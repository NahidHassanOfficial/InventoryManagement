@extends('components.layouts.dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Sales Report</h4>
                        <label class="form-label mt-2">Date From</label>
                        <input id="fromDate" type="date" class="form-control" />
                        <label class="form-label mt-2">Date To</label>
                        <input id="toDate" type="date" class="form-control" />
                        <button onclick="SalesReport()" class="btn mt-3 bg-gradient-primary">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    function SalesReport() {
        let fromDate = document.getElementById('fromDate').value;
        let toDate = document.getElementById('toDate').value;
        if (fromDate.length === 0 || toDate.length === 0) {
            errorToast("Date Range Required !")
        } else {
            window.open('/sales-report/' + fromDate + '/' + toDate);
        }
    }
</script>
