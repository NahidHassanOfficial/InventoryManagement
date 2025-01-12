<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameUpdate">

                                <label class="form-label mt-3">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailUpdate">

                                <label class="form-label mt-3">Customer Phone *</label>
                                <input type="text" class="form-control" id="customerPhoneUpdate">

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function Update() {

        let customerName = document.getElementById('customerNameUpdate').value;
        let customerEmail = document.getElementById('customerEmailUpdate').value;
        let customerPhone = document.getElementById('customerPhoneUpdate').value;
        let updateID = document.getElementById('updateID').value;


        if (customerName.length === 0) {
            errorToast("Customer Name Required !")
        } else if (customerEmail.length === 0) {
            errorToast("Customer Email Required !")
        } else if (customerPhone.length === 0) {
            errorToast("Customer phone Required !")
        } else {

            document.getElementById('update-modal-close').click();

            showLoader();
            try {
                let res = await axios.patch("{{ route('customer.update') }}", {
                    name: customerName,
                    email: customerEmail,
                    phone: customerPhone,
                    id: updateID
                })
                if (res.status === 200) {

                    successToast('Request completed');

                    document.getElementById("update-form").reset();

                    await getList();
                }
            } catch (
                error) {
                errorToast('Request Failed');
            } finally {
                hideLoader();
            }

        }

    }
</script>
