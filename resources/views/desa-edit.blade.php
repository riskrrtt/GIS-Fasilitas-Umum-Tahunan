@extends('/layout/layout')
@push('duar')
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid mt-5">
            <h1 class="h3 mb-2 text-gray-800">Edit desa</h1>
            <!-- Content Row -->
            <div class="row">
                <!-- Area Chart -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Lokasi desa</h6>
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
                            <h6 class="m-0 font-weight-bold text-primary">Data desa</h6>
                        </div>
                        <div class="card-body">
                            <div>
                                <form method="POST" action="{{ route('desa-update', $desa->id) }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="nama_desa">Nama desa</label>
                                                <input value="{{ $desa->nama}}" type="text" class="form-control"
                                                    name="nama_desa" id="nama_desa" placeholder="Ex: peguyangan" required>
                                            </div>
                                        </div>
                                        <div class="form-row mt-3">
                                            <div class="col">
                                                <label for="koordinat">Data koordinat</label>
                                                <input value="{{ $desa->area}}" type="text-area" class="form-control"
                                                    name="koordinat" id="koordinat" placeholder="" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <a onclick='this.parentNode.submit(); return false;'
                                        class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Simpan data</span>
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
            $('#desa').addClass('active');
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

        var semuaDesa = {!! json_encode($semuaDesa->toArray()) !!}
        var sekolah = {!! json_encode($sekolah->toArray()) !!}
        var pasar = {!! json_encode($pasar->toArray()) !!}
        var tempatIbadah = {!! json_encode($tempatIbadah->toArray()) !!}

        semuaDesa.forEach(element => {
            var id = jQuery.parseJSON(element['id']);
            if (element['area']) {
                // Don't draw the current village as 'blue' background, it will be drawn as 'red' editable below
                if (element['id'] != {{ $desa->id }}) {
                    var bruh = JSON.parse(element['area']);
                    L.polyline(bruh, { id: id, color: 'blue' }).addTo(mainMap).bindPopup(element['nama']);
                }
            }
        });
        sekolah.forEach(element => {
            L.marker([element['lat'], element['lng']], { icon: sekolahIcon }).addTo(mainMap).bindPopup(element['nama']);
        });
        pasar.forEach(element => {
            L.marker([element['lat'], element['lng']], { icon: pasarIcon }).addTo(mainMap).bindPopup(element['nama']);
        });
        tempatIbadah.forEach(element => {
            L.marker([element['lat'], element['lng']], { icon: tempatIbadahIcon }).addTo(mainMap).bindPopup(element['nama']);
        });


        var desa = {!! json_encode($desa) !!};
        var id = jQuery.parseJSON(desa['id']);
        var bruh = JSON.parse(desa['area']);
        var polygon = L.polygon(bruh, { id: id, color: 'red' }).addTo(mainMap);
        polygon.on('pm:update', e => {
            editLine(e);
        });

        polygon.on('pm:remove', e => {
            document.getElementById('koordinat').value = "";
        });


        function editLine(e) {
            var layer = e.target || e.layer;
            var coords = layer.getLatLngs();
            while (coords.length > 0 && Array.isArray(coords[0])) {
                coords = coords[0];
            }

            var data = [];
            for (var i = 0; i < coords.length; i++) {
                data.push([coords[i].lat, coords[i].lng]);
            }
            if (data.length > 0) {
                data.push(data[0]); // Explicitly close the loop
            }
            document.getElementById('koordinat').value = JSON.stringify(data);
        }

        // add toolbar to map
        mainMap.pm.addControls({
            position: 'topleft',
            drawCircle: false,
            drawMarker: false,
            rotateMode: false,
            editMode: true,
            drawCircleMarker: false,
            drawRectangle: false,
            drawPolygon: true,
            drawPolyline: false,
            dragMode: false,
            cutPolygon: false,
        });
        var freshPoly;
        // create new polyline here
        mainMap.on('pm:create', ({ layer }) => {
            layer.on('pm:edit', e => {
                editLine(e);
            });
            layer.on('pm:remove', e => {
                document.getElementById('koordinat').value = "";
            });
            var coords = layer.getLatLngs();
            while (coords.length > 0 && Array.isArray(coords[0])) {
                coords = coords[0];
            }

            console.log(coords);
            var data = [];
            for (var i = 0; i < coords.length; i++) {
                data.push([coords[i].lat, coords[i].lng]);
            }
            if (data.length > 0) {
                data.push(data[0]); // Explicitly close the loop
            }
            document.getElementById('koordinat').value = JSON.stringify(data);
        });
    </script>


    @if((session('done')))
        <script>
            $(document).ready(function () {
                alertDone('Data desa berhasil di edit')
            });
        </script>
    @endif

    @if((session('failed')))
        <script>
            $(document).ready(function () {
                alertFail('Data desa gagal di edit')
            });
        </script>
    @endif
@endpush