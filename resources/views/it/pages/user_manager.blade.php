@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            <div class="d-flex gap-1">
                @component('components.modal_add')
                    @slot('modal_body_add')
                        <div class="form-group mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control nospaces"
                                value="{{ old('username') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control isalpha spaces"
                                value="{{ old('nama_lengkap') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="dob">Tanggal Lahir</label>
                            <input type="text" name="dob" id="dob" class="form-control custom-date"
                                value="{{ old('dob') }}" placeholder="dd / mm / yyyy">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="mobile">Mobile Number</label>
                            <input type="text" name="mobile" id="mobile" class="form-control isnumber"
                                value="{{ old('mobile') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="departemen">Departemen</label>
                            <select name="departemen" id="departemen" class="select2" data-parent="modal_add">
                                <option value="" selected disabled>-- Select One --</option>
                                @foreach ($departemens as $departemen)
                                    <option value="{{ $departemen->kd_departemen }}"
                                        {{ old('departemen') == $departemen->kd_departemen ? 'selected' : '' }}>
                                        {{ $departemen->nm_departemen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="hak_akses">Hak Akses</label>
                            <select name="hak_akses" id="hak_akses" class="form-control form-select">
                                <option value="" selected disabled>-- Select One --</option>
                                @foreach ($hak_akses as $akses)
                                    <option value="{{ $akses->kd_hak_akses }}"
                                        {{ old('hak_akses') == $akses->kd_hak_akses ? 'selected' : '' }}>
                                        {{ $akses->nm_hak_akses }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="cabang">Cabang</label>
                            <select name="cabang" id="cabang" class="form-control form-select">
                                <option value="" selected disabled>-- Select One --</option>
                                @foreach ($cabangs as $cabang)
                                    <option value="{{ $cabang->kd_cabang }}"
                                        {{ old('cabang') == $cabang->kd_cabang ? 'selected' : '' }}>
                                        {{ $cabang->nm_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endslot
                @endcomponent

                @include('components.upload_excel')
            </div>


            @component('components.table_data', ['dataTableType' => 'js-dataTable-buttons'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor HP</th>
                        <th>Departemen</th>
                        <th>Hak Akses</th>
                        <th>Cabang</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @php $i=0; @endphp
                    @foreach ($users as $item)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td>{{ $item->nm_user }}</td>
                            <td>{{ $item->mobile_user }}</td>
                            <td>{{ $item->departemen->nm_departemen }}</td>
                            <td>{{ $item->hakAkses->nm_hak_akses }}</td>
                            <td>{{ $item->cabang->nm_cabang }}</td>
                            <td class="d-flex justify-content-center gap-1">
                                @component('components.modal_update', ['index' => $i])
                                    @slot('modal_body_update')
                                        <input type="hidden" name="user_id" value="{{ $item->id }}">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" id="nama_lengkap{{ $i }}"
                                                class="form-control isalpha spaces" value="{{ $item->nm_user }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="dob">Tanggal Lahir</label>
                                            <input type="text" name="dob" id="dob{{ $i }}"
                                                class="form-control custom-date" value="{{ $item->dob_user }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="mobile">Mobile Number</label>
                                            <input type="text" name="mobile" id="mobile{{ $i }}"
                                                class="form-control isnumber" value="{{ $item->mobile_user }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="departemen">Departemen</label>
                                            <select name="departemen" id="departemen{{ $i }}" class="select2"
                                                data-parent="modal_update{{ $i }}">
                                                <option value="" selected disabled>-- Select One --
                                                </option>
                                                @foreach ($departemens as $departemen)
                                                    <option value="{{ $departemen->kd_departemen }}"
                                                        {{ $item->kd_departemen == $departemen->kd_departemen ? 'selected' : '' }}>
                                                        {{ $departemen->nm_departemen }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="hak_akses">Hak Akses</label>
                                            <select name="hak_akses" id="hak_akses{{ $i }}" class="form-select">
                                                <option value="" selected disabled>-- Select One --
                                                </option>
                                                @foreach ($hak_akses as $akses)
                                                    <option value="{{ $akses->kd_hak_akses }}"
                                                        {{ $item->hak_akses == $akses->kd_hak_akses ? 'selected' : '' }}>
                                                        {{ $akses->nm_hak_akses }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="cabang">Cabang</label>
                                            <select name="cabang" id="cabang{{ $i }}" class="form-select">
                                                <option value="" selected disabled>-- Select One --
                                                </option>
                                                @foreach ($cabangs as $cabang)
                                                    <option value="{{ $cabang->kd_cabang }}"
                                                        {{ $item->kd_cabang === $cabang->kd_cabang ? 'selected' : '' }}>
                                                        {{ $cabang->nm_cabang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endslot
                                @endcomponent
                                @if ($item->id != auth()->user()->id)
                                    @component('components.alert_delete', ['index' => $i])
                                        @slot('deleteBody')
                                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                                        @endslot
                                        @slot('deleteName')
                                            {{ $item->nm_user }}
                                        @endslot
                                    @endcomponent

                                    <form action="" method="post">
                                        @csrf
                                        <input type="hidden" name="impersonate" id="impersonate" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-success btn-sm"><i
                                                class="ti ti-login fs-4"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@section('scripts')
    @if (old('username'))
        <script nonce="{{ $nonce }}">
            $(document).ready(function() {
                $('#modal_add_user').modal('show');
            })
        </script>
    @endif
@endsection
