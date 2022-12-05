<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Input Data</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.min.css" integrity="sha512-oIQ0EBio8LJupRpgmDsIsvm0Fsr6c3XNHLB7at5xb+Cf6eQuCX9xuX8XXGRIcokNgdqL1ms7nqbQ6ryXMGxXpg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        #map {
            height: 500px;
            width: 100%;
        }

        .container {
            margin-top: 70px;
        }
    </style>
</head>

<body>

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
                            <a class="nav-link" href="<?= base_url(); ?>">
                                <i class="fas fa-map-marked-alt mx-1"></i></i>Peta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('objek/table'); ?>">
                                <i class="fas fa-table mx-1"></i>Tabel Data</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link active <?= auth()->loggedIn() ? 'text-danger' : '' ?>" href="<?= auth()->loggedIn() ? base_url('logout') : base_url('login') ?>">
                            <i class="fas fa-sign-in-alt "></i>
                            <?= auth()->loggedIn() ? 'Keluar' : 'Masuk' ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card my-3 mb-3">
            <div class="card-header text-center">
                <h3>Input Data</h3>
            </div>

            <div class="card-body">
                <form action="<?= base_url('objek/simpantambahdata') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label for="input_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control <?= ($validation->hasError('input_nama')) ? 'is-invalid' : '' ?>" id=" input_nama" name="input_nama" placeholder="Isikan nama objek" value="<?= set_value('input_nama'); ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('input_nama'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="input_deskripsi" name="input_deskripsi" placeholder="Isikan deskripsi objek" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="input_longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control <?= ($validation->hasError('input_longitude')) ? 'is-invalid' : '' ?>" id="input_longitude" name="input_longitude" placeholder="Isikan longitude" value="<?= set_value('input_longitude'); ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('input_longitude'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input_latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control <?= ($validation->hasError('input_latitude')) ? 'is-invalid' : '' ?>" id="input_latitude" name="input_latitude" placeholder="Isikan latitude" value="<?= set_value('input_latitude'); ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('input_latitude'); ?>
                        </div>
                    </div>

                    <div id="map"></div>

                    <div class="mb-3">
                        <label for="input_foto" class="form-label">Foto</label>
                        <input type="file" class="form-control <?= ($validation->hasError('input_foto')) ? 'is-invalid' : '' ?>" id="input_foto" name="input_foto">
                        <div class="invalid-feedback">
                            <?= $validation->getError('input_foto'); ?>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <!-- <a type="button" href="<?= base_url(); ?>" class="btn btn-success">Tampilkan Peta</a>
                        <a type="button" href="<?= base_url('objek/table'); ?>" class="btn btn-success">Tampilkan Tabel Data</a> -->
                        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.min.js" integrity="sha512-TL+GX2RsOUlTndpkgHVnSQ9r6zldqHzfyECrdabkpucdFroZ3/HAhMmP2WYaPjsJCoot+0McmdPOLjmmicG9qg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var center = [-6.7336086, 107.0389104];

        var map = L.map('map').setView(center, 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        //marker
        var marker = L.marker(center, {
            draggable: true
        }).addTo(map);

        //event
        marker.on('dragend', function(e) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            document.getElementById("input_latitude").value = lat;
            document.getElementById("input_longitude").value = lng;
        });

        //map movement
        map.on('moveend', function(event) {
            //get center
            var center = map.getCenter();
            var lng = center.lng;
            var lat = center.lat;
            //set marker position
            marker.setLatLng(center, {
                draggable: true
            }).update();
            //set input value
            document.getElementById("input_latitude").value = lat;
            document.getElementById("input_longitude").value = lng;
        })
    </script>
</body>

</html>