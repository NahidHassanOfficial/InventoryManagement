<div class="container my-5">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Shop Profile</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="col m-0 p-0">

                            <div class="col-md-4 p-2">
                                <label>Shop Title</label>
                                <input id="title" placeholder="Enter Title" class="form-control" type="text" />
                            </div>
                            <img class="w-15" id="newLogo" src="" />
                            <div class="col-md-4 p-2">
                                <label>Shop Logo</label>
                                <input id="logo" class="form-control" type="file"
                                    oninput="newLogo.src=window.URL.createObjectURL(this.files[0])" />
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onShopUpdate()"
                                    class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    getShop();
    async function getShop() {
        try {
            showLoader();
            let res = await axios.get("{{ route('shop.info') }}")
            hideLoader();
            if (res.status === 200 && res.data['status'] === 'success') {
                let data = res.data['data'];
                document.getElementById('title').value = data['title'];
                document.getElementById('newLogo').src = `uploads/logo/${data['logo']}`;
            } else {
                errorToast(res.data['message'])
            }
        } catch (error) {
            errorToast(error.response.data.message)
        } finally {
            hideLoader();
        }
    }

    async function onShopUpdate() {
        showLoader();

        let logoFile = document.getElementById('logo').files[0];
        let formData = new FormData();
        formData.append('title', document.getElementById('title').value);
        if (logoFile) {
            formData.append('logo', logoFile);
        }
        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }
        try {
            let res = await axios.post("{{ route('shop.update') }}", formData, config);
            hideLoader();
            if (res.status === 200 && res.data['status'] === 'success') {
                // window.location.reload();
                successToast(res.data['message']);
            } else {
                errorToast(res.data['message'])
            }
        } catch (error) {
            errorToast(error.response.data.message)
        } finally {
            hideLoader();
        }

    }
</script>
