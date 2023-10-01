@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            <div class="row">
                <div class="col-md-4 mx-auto">
                    <form action="" method="post" id="form_profile" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="update_profile">
                        <div class="d-block text-center w-100 mb-4">
                            <img class="avatar-profile rounded-circle m-3 mb-4 border border-3 border-accent p-1" src="{{ auth()->user()->preview_profile }}"
                                alt="Profile Picture" width="150">
                            <input type="file" class="form-control" name="profile_img" id="profile_img" accept="image/*">
                        </div>
                    </form>
                </div>
            </div>


            <form action="" method="post" id="form_account" class="js-validate-form">
                @csrf
                @include('components.honeypot')
                <div class="mb-3">
                    <label class="form-label small mb-1" for="username">Username</label>
                    <input class="form-control" id="username" type="text" value="{{ auth()->user()->username }}"
                        readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label small mb-1" for="departemen">Departemen</label>
                    <input class="form-control" id="departemen" type="text"
                        value="{{ auth()->user()->departemen->nm_departemen }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label small mb-1" for="nm_lengkap">Nama Lengkap</label>
                    <input class="form-control" name="nm_lengkap" id="nm_lengkap" type="text"
                        placeholder="Enter your fullname" value="{{ auth()->user()->nm_user }}">
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small mb-1" for="mobile">Nomor Handphone</label>
                        <input class="form-control isnumber" name="mobile" id="mobile" type="text"
                            placeholder="Enter your mobile number" value="{{ auth()->user()->mobile_user }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small mb-1" for="dob">Tanggal Lahir</label>
                        <input class="form-control" name="dob" id="dob" type="text"
                            placeholder="Enter your birthdate" value="{{ $formatted_dob }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small mb-1" for="cur_pass">Current Password</label>
                    <input class="form-control" name="cur_pass" id="cur_pass" type="password"
                        placeholder="Enter your current password">
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small mb-1" for="password">New Password (Optional)</label>
                        <input class="form-control optional enhanced" name="password" id="password" type="password"
                            placeholder="Enter new password">
                        @include('components.password_requirements')
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small mb-1" for="password_confirmation">Password Confirmation</label>
                        <input class="form-control optional enhanced" name="password_confirmation"
                            id="password_confirmation" type="password" placeholder="Enter password confirmation">
                    </div>
                </div>
                <input type="hidden" name="update_account">
                <button class="btn btn-success mb-3" type="submit">Save changes</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            $('#profile_img').change(function() {
                $('#form_profile').submit();
            })
        });
    </script>
@endsection
