<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">


                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnitUpdate">
                                <br />
                                <img class="w-15" id="oldImg" src="" />
                                <br />
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])" type="file"
                                    class="form-control" id="productImgUpdate">

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" class="d-none" id="img_url">


                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>

        </div>
    </div>
</div>


<script>
    async function UpdateFillCategoryDropDown() {
        let res = await axios.get("{{ route('category.data') }}")
        res.data.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`
            $("#productCategoryUpdate").append(option);
        })
    }


    async function FillUpUpdateForm(id, img_url) {

        document.getElementById('updateID').value = id;
        document.getElementById('img_url').value = img_url;
        document.getElementById('oldImg').src = img_url;


        showLoader();
        await UpdateFillCategoryDropDown();

        let res = await axios.get("{{ route('product.info') }}", {
            params: {
                id: id
            }
        })
        hideLoader();

        document.getElementById('productNameUpdate').value = res.data['name'];
        document.getElementById('productPriceUpdate').value = res.data['price'];
        document.getElementById('productUnitUpdate').value = res.data['unit'];
        document.getElementById('productCategoryUpdate').value = res.data['category_id'];
        document.getElementById('oldImg').src = `uploads/products/${res.data['img_url']}`;

    }



    async function update() {

        let updateID = document.getElementById('updateID').value;
        let productNameUpdate = document.getElementById('productNameUpdate').value;
        let productPriceUpdate = document.getElementById('productPriceUpdate').value;
        let productUnitUpdate = document.getElementById('productUnitUpdate').value;
        let productCategoryUpdate = document.getElementById('productCategoryUpdate').value;
        let productImgUpdate = document.getElementById('productImgUpdate').files[0];


        if (productCategoryUpdate.length === 0) {
            errorToast("Product Category Required !")
        } else if (productNameUpdate.length === 0) {
            errorToast("Product Name Required !")
        } else if (productPriceUpdate.length === 0) {
            errorToast("Product Price Required !")
        } else if (productUnitUpdate.length === 0) {
            errorToast("Product Unit Required !")
        } else {
            document.getElementById('update-modal-close').click();

            let formData = new FormData();
            formData.append('product_id', updateID)
            formData.append('name', productNameUpdate)
            formData.append('price', productPriceUpdate)
            formData.append('unit', productUnitUpdate)
            formData.append('category_id', parseInt(productCategoryUpdate))
            formData.append('img', productImgUpdate)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            try {
                showLoader();
                let res = await axios.post("{{ route('product.update') }}", formData, config)
                hideLoader();

                if (res.status === 201) {
                    successToast(res.data['message']);
                    document.getElementById("update-form").reset();
                    await getList();
                } else {
                    errorToast(res.data['message'])
                }
            } catch (error) {
                errorToast(error.response.data.message)
            } finally {
                hideLoader();
            }
        }
    }
</script>