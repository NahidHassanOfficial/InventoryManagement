<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>phone Number</label>
                                <input id="phone" placeholder="phone" class="form-control" type="phone" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control"
                                    type="password" />
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()"
                                    class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function onRegistration() {

        try {
            let email = document.getElementById('email').value;
            let firstName = document.getElementById('firstName').value;
            let lastName = document.getElementById('lastName').value;
            let phone = document.getElementById('phone').value;
            let password = document.getElementById('password').value;

            if (email.length === 0) {
                errorToast('Email is required')
            } else if (firstName.length === 0) {
                errorToast('First Name is required')
            } else if (lastName.length === 0) {
                errorToast('Last Name is required')
            } else if (phone.length === 0) {
                errorToast('phone is required')
            } else if (password.length === 0) {
                errorToast('Password is required')
            } else {
                showLoader();
                let res = await axios.post('{{ route('userRegister') }}', {
                    email: email,
                    firstName: firstName,
                    lastName: lastName,
                    phone: phone,
                    password: password
                })
                if (res.status === 200 && res.data['status'] === 'success') {
                    successToast(res.data['message']);
                    setTimeout(function() {
                        window.location.href = '{{ route('loginPage') }}'
                    }, 2000)
                } else {
                    errorToast(res.data['message'])
                }
            }
        } catch (error) {
            errorToast(error.response.data.message)
        } finally {
            hideLoader();
        }
    }
</script>
