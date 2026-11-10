<?php
class Pesanan
{
    private $pdo;

    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, m.nama_menu
             FROM pesanan p INNER JOIN menu m ON p.id_menu = m.id_menu
             ORDER BY p.tanggal_pesanan DESC, p.id_pesanan DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pesanan WHERE id_pesanan = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getMenus()
    {
        $stmt = $this->pdo->prepare("SELECT id_menu, nama_menu, harga, stok FROM menu ORDER BY nama_menu");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function getMenuPrice($idMenu)
    {
        $stmt = $this->pdo->prepare("SELECT harga FROM menu WHERE id_menu = :id");
        $stmt->execute([':id' => $idMenu]);
        $menu = $stmt->fetch();
        if (!$menu) {
            throw new InvalidArgumentException('Menu yang dipilih tidak ditemukan.');
        }
        return (float) $menu['harga'];
    }

    public function create($data)
    {
        $harga = $this->getMenuPrice($data['id_menu']);
        $total = $harga * (int) $data['jumlah'];
        $stmt = $this->pdo->prepare(
            "INSERT INTO pesanan (id_menu, nama_pelanggan, jumlah, total_harga, tanggal_pesanan, status_pesanan)
             VALUES (:menu, :pelanggan, :jumlah, :total, :tanggal, :status)"
        );
        return $stmt->execute([
            ':menu' => $data['id_menu'],
            ':pelanggan' => trim($data['nama_pelanggan']),
            ':jumlah' => (int) $data['jumlah'],
            ':total' => $total,
            ':tanggal' => $data['tanggal_pesanan'],
            ':status' => $data['status_pesanan']
        ]);
    }

    public function update($id, $data)
    {
        $harga = $this->getMenuPrice($data['id_menu']);
        $total = $harga * (int) $data['jumlah'];
        $stmt = $this->pdo->prepare(
            "UPDATE pesanan SET id_menu = :menu, nama_pelanggan = :pelanggan,
             jumlah = :jumlah, total_harga = :total, tanggal_pesanan = :tanggal,
             status_pesanan = :status WHERE id_pesanan = :id"
        );
        return $stmt->execute([
            ':menu' => $data['id_menu'],
            ':pelanggan' => trim($data['nama_pelanggan']),
            ':jumlah' => (int) $data['jumlah'],
            ':total' => $total,
            ':tanggal' => $data['tanggal_pesanan'],
            ':status' => $data['status_pesanan'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM pesanan WHERE id_pesanan = :id");
        return $stmt->execute([':id' => $id]);
    }
}
