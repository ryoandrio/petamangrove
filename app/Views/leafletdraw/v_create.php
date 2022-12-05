<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Peta Lokasi Objek</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        html,
        body,
        #map {
            height: 100%;
            width: 100%;
            margin: 0px;
            overflow: hidden;
        }

        #map {
            width: auto;
            height: calc(100% - 56px);
            margin-top: 56px;
        }
    </style>

</head>

<body>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.min.js"></script>

    <!-- Terraformer -->
    <script src="https://cdn.jsdelivr.net/npm/terraformer@1.0.12/terraformer.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/terraformer-wkt-parser@1.2.1/terraformer-wkt-parser.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <nav class="navbar navbar-expand-lg bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand " href="#">
                <i class="fas fa-map-marked-alt"></i>
                Peta Lokasi Objek</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <?php if (auth()->loggedIn()) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('leafletdraw/edit'); ?>">
                                <i class="fas fa-edit mx-1"></i>Edit Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?= base_url('leafletdraw/delete'); ?>">
                                <i class="fas fa-trash mx-1"></i>Hapus Data</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link active <?= auth()->loggedIn() ? 'text-danger' : '' ?>" href="<?= auth()->loggedIn() ? base_url('logout') : base_url('login') ?>">
                            <i class="fas fa-sign-in-alt "></i>
                            <?= auth()->loggedIn() ? 'Keluar' : 'Masuk' ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#infoModal">
                            <i class="fas fa-info-circle mx-1"></i>Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="map"></div>

    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Informasi
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Aplikasi peta lokasi objek ini dibuat menggunakan CodeIgniter 4 dan database PostrgeSQL.</p>

                    <p>Dibuat oleh Ryo Dwi Permana A</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Point -->
    <div class="modal fade" id="pointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Point
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('leafletdraw/simpan_point'); ?>" method="POST">
                        <?= csrf_field(); ?>
                        <label for="input_point_name">Nama</label>
                        <input type="text" class="form-control" id="input_point_name" name="input_point_name">
                        <label for="input_point_geometry">Geometry</label>
                        <textarea class="form-control" name="input_point_geometry" id="input_point_geometry" rows="2"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn_save_point">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Polyline -->
    <div class="modal fade" id="polylineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Polyline
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('leafletdraw/simpan_polyline'); ?>" method="POST">
                        <?= csrf_field(); ?>
                        <label for="input_polyline_name">Nama</label>
                        <input type="text" class="form-control" id="input_polyline_name" name="input_polyline_name">
                        <label for="input_polyline_geometry">Geometry</label>
                        <textarea class="form-control" name="input_polyline_geometry" id="input_polyline_geometry" rows="2"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn_save_point">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Polygon -->
    <div class="modal fade" id="polygonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Polygon
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('leafletdraw/simpan_polygon'); ?>" method="POST">
                        <?= csrf_field(); ?>
                        <label for="input_polygon_name">Nama</label>
                        <input type="text" class="form-control" id="input_polygon_name" name="input_polygon_name">
                        <label for="input_polygon_geometry">Geometry</label>
                        <textarea class="form-control" name="input_polygon_geometry" id="input_polygon_geometry" rows="2"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn_save_point">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* Set Up Map */
        var center = [-6.7336086, 107.0389104];
        var map = L.map('map').setView(center, 10);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        /* Draw Items */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        /* Draw Control */
        var drawControl = new L.Control.Draw({
            draw: {
                marker: true,
                polyline: true,
                polygon: true,
                circle: false,
                rectangle: false,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        /* Draw Events */
        map.on(L.Draw.Event.CREATED, function(e) {
            var type = e.layerType,
                layer = e.layer;

            // Convert Geometry to GeoJSON
            var drawnItemJson = layer.toGeoJSON().geometry;

            // Convert GeoJSON to WKT
            var drawnItemWKT = Terraformer.WKT.convert(drawnItemJson);

            if (type === 'marker') {
                // Set value to input
                $('#input_point_geometry').html(drawnItemWKT);

                // Open Modal
                $('#pointModal').modal('show');
            } else if ((type === 'polyline')) {
                // Set value to input
                $('#input_polyline_geometry').html(drawnItemWKT);

                // Open Modal
                $('#polylineModal').modal('show');
            } else if ((type === 'polygon')) {
                // Set value to input
                $('#input_polygon_geometry').html(drawnItemWKT);

                // Open Modal
                $('#polygonModal').modal('show');
            }
            // Do whatever else you need to. (save to db; add to map etc)
            map.addLayer(layer);
        });

        // GeoJSON Point
        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.nama;
                layer.on({
                    click: function(e) {
                        point.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        point.bindTooltip(feature.properties.nama);
                    },
                });
            },
        });
        $.getJSON("<?= base_url('api/point'); ?>", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        // GeoJSON Line
        var line = L.geoJson(null, {
            /* Style polyline */
            style: function(feature) {
                return {
                    color: "#3388ff",
                    weight: 3,
                    opacity: 1,
                };
            },
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.nama + "<br>" +
                    "Panjang: " + feature.properties.panjang_km + " km";
                layer.on({
                    click: function(e) {
                        line.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        line.bindTooltip(feature.properties.nama, {
                            sticky: true,
                        });
                    },
                });
            },
        });
        $.getJSON("<?= base_url('api/polyline'); ?>", function(data) {
            line.addData(data);
            map.addLayer(line);
        });

        // GeoJSON Polygon
        var polygon = L.geoJson(null, {
            /* Style polygon */
            style: function(feature) {
                return {
                    color: "#3388ff",
                    fillColor: "#3388ff",
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.2,
                };
            },
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.nama + "<br>" +
                    "Luas: " + feature.properties.luas_km2 + " km2";
                layer.on({
                    click: function(e) {
                        polygon.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        polygon.bindTooltip(feature.properties.nama, {
                            sticky: true,
                        });
                    },
                });
            },
        });
        $.getJSON("<?= base_url('api/polygon'); ?>", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
        });
    </script>
</body>

</html>