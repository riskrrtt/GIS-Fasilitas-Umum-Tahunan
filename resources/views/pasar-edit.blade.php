@extends('/layout/layout')
@push('duar')
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid mt-5">
            <h1 class="h3 mb-2 text-gray-800">Edit pasar</h1>
            <!-- Content Row -->
            <div class="row">
                <!-- Area Chart -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Lokasi pasar</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div>
                                <div id="mapid"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Data pasar</h6>
                        </div>
                        <div class="card-body">
                            <div>
                                <form method="POST" action="{{ route('pasar-update', $pasar->id) }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="nama_pasar">Nama pasar</label>
                                                <input value="{{ $pasar->nama }}" type="text" class="form-control"
                                                    name="nama_pasar" id="nama_pasar" placeholder="Ex: Pasar peguyangan"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="desa">Desa</label>
                                            </div>
                                            <select class="custom-select" id="desa" name="desa">
                                                @foreach ($desa as $desas)
                                                    @if ($desas->id == $pasar->id_desa)
                                                        <option selected value={{ $desas->id }}>{{ $desas->nama}}</option>
                                                    @else
                                                        <option value={{ $desas->id }}>{{ $desas->nama}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-row mt-3">
                                            <div class="col">
                                                <label for="alamat_pasar">Alamat pasar</label>
                                                <input value="{{ $pasar->alamat }}" type="text" class="form-control"
                                                    name="alamat_pasar" id="alamat_pasar" placeholder="Ex: Jalan astasura"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="form-row mt-3">
                                            <div class="col">
                                                <label for="lat">Data latitude</label>
                                                <input value={{ $pasar->lat }} type="text-area" class="form-control"
                                                    name="lat" id="lat" placeholder="" required readonly>
                                            </div>
                                        </div>
                                        <div class="form-row mt-3">
                                            <div class="col">
                                                <label for="lng">Data longitude</label>
                                                <input value={{ $pasar->lng }} type="text-area" class="form-control"
                                                    name="lng" id="lng" placeholder="" required readonly>
                                            </div>
                                        </div>

                                    </div>
                                    <a onclick='this.parentNode.submit(); return false;'
                                        class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Simpan data pasar</span>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection
<!-- End of Main Content -->
@push('anjay')
    <script>
        $(document).ready(function () {
            $('#pasar').addClass('active');
        });

        let mainMap = L.map('mapid').setView([-6.617, 110.690], 14);

        // Using OpenTopoMap (free, no API key required, better terrain view)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Source: USGS, Esri, TANA, DeLorme, and NPS'
        }).addTo(mainMap);

        var markerIcon = L.Icon.extend({
            options: {
                iconSize: [40, 40]
            }
        });
        var pasarIcon = new markerIcon({ iconUrl: '{{ URL::asset('icon/marker_pasar.png') }}' }),
            sekolahIcon = new markerIcon({ iconUrl: '{{ URL::asset('icon/marker_sekolah.png') }}' }),
            tempatIbadahIcon = new markerIcon({ iconUrl: '{{ URL::asset('icon/marker_agama.png') }}' });

        var desa = {!! json_encode($desa->toArray()) !!}
        var sekolah = {!! json_encode($sekolah->toArray()) !!}
        var semuaPasar = {!! json_encode($semuaPasar->toArray()) !!}
        var tempatIbadah = {!! json_encode($tempatIbadah->toArray()) !!}

        desa.forEach(element => {
            var id = jQuery.parseJSON(element['id']);
            if (element['area']) {
                var bruh = JSON.parse(element['area']);
                L.polyline(bruh, { id: id, color: 'red' }).addTo(mainMap);
            }
        });
        sekolah.forEach(element => {
            L.marker([element['lat'], element['lng']], { icon: sekolahIcon }).addTo(mainMap).bindPopup(element['nama']);
        });
        tempatIbadah.forEach(element => {
            L.marker([element['lat'], element['lng']], { icon: tempatIbadahIcon }).addTo(mainMap).bindPopup(element['nama']);
        });
        semuaPasar.forEach(element => {
            L.marker([element['lat'], element['lng']], { icon: pasarIcon }).addTo(mainMap).bindPopup(element['nama']);
        });

        var pasar = {!! json_encode($pasar) !!};
        var marker;
        marker = L.marker([pasar.lat, pasar.lng]).bindPopup().addTo(mainMap);
        mainMap.on('click', function (e) {
            if (marker) { // check
                mainMap.removeLayer(marker); // remove
            }
            marker = L.marker(e.latlng, {}).addTo(mainMap);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });
    </script>


    @if((session('done')))
        <script>
            $(document).ready(function () {
                alertDone('Data pasar berhasil di edit')
            });
        </script>
    @endif

    @if((session('failed')))
        <script>
            $(document).ready(function () {
                alertFail('Data pasar gagal di edit')
            });
        </script>
    @endif
@endpush