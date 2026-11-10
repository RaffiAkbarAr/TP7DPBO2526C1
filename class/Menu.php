<?php
class Menu
{
    private $pdo;

    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, k.nama_kategori
             FROM menu m INNER JOIN kategori k ON m.id_kategori = k.id_kategori
             ORDER BY m.id_menu DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM menu WHERE id_menu = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getCategories()
    {
        $stmt = $this->pdo->prepare("SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO menu (id_kategori, nama_menu, jenis_menu, harga, stok, deskripsi)
             VALUES (:kategori, :nama, :jenis, :harga, :stok, :deskripsi)"
        );
        return $stmt->execute([
            ':kategori' => $data['id_kategori'],
            ':nama' => trim($data['nama_menu']),
            ':jenis' => $data['jenis_menu'],
            ':harga' => $data['harga'],
            ':stok' => $data['stok'],
            ':deskripsi' => trim($data['deskripsi'] ?? '')
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE menu SET id_kategori = :kategori, nama_menu = :nama,
             jenis_menu = :jenis, harga = :harga, stok = :stok, deskripsi = :deskripsi
             WHERE id_menu = :id"
        );
        return $stmt->execute([
            ':kategori' => $data['id_kategori'],
            ':nama' => trim($data['nama_menu']),
            ':jenis' => $data['jenis_menu'],
            ':harga' => $data['harga'],
            ':stok' => $data['stok'],
            ':deskripsi' => trim($data['deskripsi'] ?? ''),
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM menu WHERE id_menu = :id");
        return $stmt->execute([':id' => $id]);
    }
}
