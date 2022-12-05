<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelMangrove extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'geom' => [
                'type' => 'GEOMETRY',
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'sumber' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'tahun' => [
                'type'       => 'INT',
                'constraint' => '255',
                'null' => true,
            ],
            'luas' => [
                'type'       => 'DECIMAL(20,15)',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tbl_mangrove');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_mangrove');
    }
}
