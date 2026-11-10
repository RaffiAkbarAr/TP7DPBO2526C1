<?php
class Kategori
{
    private $pdo;

    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT k.*, COUNT(m.id_menu) AS jumlah_menu
             FROM kategori k LEFT JOIN menu m ON k.id_kategori = m.id_kategori
             GROUP BY k.id_kategori ORDER BY k.id_kategori DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM kategori WHERE id_kategori = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO kategori (nama_kategori, deskripsi)
             VALUES (:nama, :deskripsi)"
        );
        return $stmt->execute([
            ':nama' => trim($data['nama_kategori']),
            ':deskripsi' => trim($data['deskripsi'] ?? '')
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE kategori SET nama_kategori = :nama, deskripsi = :deskripsi
             WHERE id_kategori = :id"
        );
        return $stmt->execute([
            ':nama' => trim($data['nama_kategori']),
            ':deskripsi' => trim($data['deskripsi'] ?? ''),
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM kategori WHERE id_kategori = :id");
        return $stmt->execute([':id' => $id]);
    }
}
