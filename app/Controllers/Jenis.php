<?php

namespace App\Controllers;

use App\Models\TblJenisModel;
use App\Controllers\BaseController;
use Psr\Log\NullLogger;

class Jenis extends BaseController
{

    protected $TblJenisModel;

    public function __construct()
    {
        $this->TblJenisModel = new TblJenisModel();
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
            'input_jenis' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom jenis jenis harus diisi'
                ]
            ],
            'input_longitude' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kolom longitude jenis harus diisi',
                    'numeric' => 'Kolom longitude jenis harus berupa angka'
                ]
            ],
            'input_latitude' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kolom longitude jenis harus diisi',
                    'numeric' => 'Kolom longitude jenis harus berupa angka'
                ]
            ],
            'input_foto' => [
                'rules' => 'mime_in[input_foto,image/jpg,image/jpeg,image/png]|max_size[input_foto,5024]',
                'errors' => [
                    'mime_in' => 'File yang diunggah harus berupa gambar JPG/JPEG/PNG',
                    'max_size' => 'Ukuran foto maksimal 5 MB'
                ]
            ]
        ])) {
            return redirect()->to('jenis')->with("message", 'Gagal menambahkan data lokasi jenis.')->withInput();
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
            $nama_foto = 'foto_' . preg_replace('/\s+/', '', $_POST['input_jenis'] . '.' . $file_foto->getExtension());

            $file_foto->move($foto_dir, $nama_foto);
        }


        $data = [
            'jenis' => $_POST['input_jenis'],
            'deskripsi' => $_POST['input_deskripsi'],
            'longitude' => $_POST['input_longitude'],
            'latitude' => $_POST['input_latitude'],
            'foto' => $nama_foto
        ];

        $this->TblJenisModel->save($data);

        return redirect()->to('jenis/table')->with('message', 'Data berhasil ditambahkan');
    }

    public function view()
    {
        return view('v_data');
    }

    public function table()
    {

        $data['jenis'] = $this->TblJenisModel->findAll();

        return view('v_table', $data);
    }

    public function hapus($id)
    {
        $this->TblJenisModel->delete($id);

        return redirect()->to('jenis/table')->with('message', 'Data berhasil dihapus');
    }

    public function edit($id)
    {
        $data['jenis'] = $this->TblJenisModel->find($id);

        return view('v_edit', $data);
    }

    public function simpaneditdata($id)
    {
        session();

        //Validation
        if (!$this->validate([
            'input_jenis' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kolom jenis jenis harus diisi'
                ]
            ],
            'input_longitude' => [
                'rules' => ['required', 'numeric'],
                'errors' => [
                    'required' => 'Kolom longitude jenis harus diisi',
                    'numeric' => 'Kolom longitude jenis harus berupa angka'
                ]
            ],
            'input_latitude' => [
                'rules' => ['required', 'numeric'],
                'errors' => [
                    'required' => 'Kolom longitude jenis harus diisi',
                    'numeric' => 'Kolom longitude jenis harus berupa angka'
                ]
            ],
            'input_foto' => [
                'rules' => 'mime_in[input_foto,image/jpg,image/jpeg,image/png]|max_size[input_foto,5024]',
                'errors' => [
                    'mime_in' => 'File yang diunggah harus berupa gambar',
                    'max_size' => 'Ukuran foto maksimal 5 MB'
                ]
            ]
        ])) {
            return redirect()->to('jenis')->with("message", 'Gagal menambahkan data lokasi jenis.')->withInput();
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
            'jenis' => $_POST['input_jenis'],
            'deskripsi' => $_POST['input_deskripsi'],
            'longitude' => $_POST['input_longitude'],
            'latitude' => $_POST['input_latitude'],
            'foto' => $nama_foto
        ];

        $this->TblJenisModel->save($data);

        return redirect()->to('jenis/table')->with('message', 'Data berhasil diubah');
    }
}
