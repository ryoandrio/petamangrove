<?php

namespace App\Controllers;

use App\Models\TblObjekModel;
use App\Controllers\BaseController;
use Psr\Log\NullLogger;

class Objek extends BaseController
{

    protected $TblObjekModel;

    public function __construct()
    {
        $this->TblObjekModel = new TblObjekModel();
        $this->data['validation'] = \Config\Services::validation();
    }

    public function index()
    {
        session();
        return view('v_input', $this->data);
    }

    public function simpantambahdata()
    {
        //Validation
        if (!$this->validate([
            'input_nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom nama objek harus diisi'
                ]
            ],
            'input_longitude' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kolom longitude objek harus diisi',
                    'numeric' => 'Kolom longitude objek harus berupa angka'
                ]
            ],
            'input_latitude' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kolom longitude objek harus diisi',
                    'numeric' => 'Kolom longitude objek harus berupa angka'
                ]
            ],
            'input_foto' => [
                'rules' => 'mime_in[input_foto,image/jpg,image/jpeg,image/png]|max_size[input_foto,1024]',
                'errors' => [
                    'mime_in' => 'File yang diunggah harus berupa gambar JPG/JPEG/PNG',
                    'max_size' => 'Ukuran foto maksimal 1 MB'
                ]
            ]
        ])) {
            return redirect()->to('objek')->with("message", 'Gagal menambahkan data lokasi objek.')->withInput();
        }

        //Upload Foto
        $file_foto = $this->request->getFile('input_foto');

        if ($file_foto->getError() == 4) {
            $nama_foto = null;
        } else {
            $foto_dir = 'upload/foto/';
            if (!is_dir($foto_dir)) {
                mkdir($foto_dir, 0777, true);
            }
            $nama_foto = 'foto_' . preg_replace('/\s+/', '', $_POST['input_nama'] . '.' . $file_foto->getExtension());

            $file_foto->move($foto_dir, $nama_foto);
        }


        $data = [
            'nama' => $_POST['input_nama'],
            'deskripsi' => $_POST['input_deskripsi'],
            'longitude' => $_POST['input_longitude'],
            'latitude' => $_POST['input_latitude'],
            'foto' => $nama_foto
        ];

        $this->TblObjekModel->save($data);

        return redirect()->to('objek/table')->with('message', 'Data berhasil ditambahkan');
    }

    public function view()
    {
        return view('v_data');
    }

    public function table()
    {

        $data['objek'] = $this->TblObjekModel->findAll();

        return view('v_table', $data);
    }

    public function hapus($id)
    {
        $this->TblObjekModel->delete($id);

        return redirect()->to('objek/table')->with('message', 'Data berhasil dihapus');
    }

    public function edit($id)
    {
        $data['objek'] = $this->TblObjekModel->find($id);

        return view('v_edit', $data);
    }

    public function simpaneditdata($id)
    {
        session();

        //Validation
        if (!$this->validate([
            'input_nama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom nama objek harus diisi'
                ]
            ],
            'input_longitude' => [
                'rules' => ['required', 'numeric'],
                'errors' => [
                    'required' => 'Kolom longitude objek harus diisi',
                    'numeric' => 'Kolom longitude objek harus berupa angka'
                ]
            ],
            'input_latitude' => [
                'rules' => ['required', 'numeric'],
                'errors' => [
                    'required' => 'Kolom longitude objek harus diisi',
                    'numeric' => 'Kolom longitude objek harus berupa angka'
                ]
            ],
            'input_foto' => [
                'rules' => 'mime_in[input_foto,image/jpg,image/jpeg,image/png]|max_size[input_foto,1024]',
                'errors' => [
                    'mime_in' => 'File yang diunggah harus berupa gambar',
                    'max_size' => 'Ukuran foto maksimal 1 MB'
                ]
            ]
        ])) {
            return redirect()->to('objek')->with("message", 'Gagal menambahkan data lokasi objek.')->withInput();
        }

        //Upload Foto
        $file_foto = $this->request->getFile('input_foto');

        if ($file_foto->getError() == 4) {
            if ($_POST['input_foto_lama'] !== '') {
                $nama_foto = $_POST['input_foto_lama'];
            } else {
                $nama_foto = NULL;
            }
        } else {
            $foto_dir = 'upload/foto/';
            if (!is_dir($foto_dir)) {
                mkdir($foto_dir, 0777, TRUE);
            }
            if ($_POST['input_foto_lama'] !== '') {
                if (file_exists($foto_dir . $_POST['input_foto_lama'])) {
                    unlink($foto_dir . $_POST['input_foto_lama']);
                }
            }
            $nama_foto = 'foto_' . preg_replace('/\s+/', '', $_POST['input_nama']) . '.' . $file_foto->getExtension();
            $file_foto->move($foto_dir, $nama_foto);
        }

        $data = [
            'id' => $id,
            'nama' => $_POST['input_nama'],
            'deskripsi' => $_POST['input_deskripsi'],
            'longitude' => $_POST['input_longitude'],
            'latitude' => $_POST['input_latitude'],
            'foto' => $nama_foto
        ];

        $this->TblObjekModel->save($data);

        return redirect()->to('objek/table')->with('message', 'Data berhasil diubah');
    }
}
