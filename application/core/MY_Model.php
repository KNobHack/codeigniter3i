<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Fany Muhammad Fahmi Kamilah
 */
class MY_Model extends CI_Model
{
    /**
     * Nama tabel
     * 
     * @var string $table
     */
    public $table;

    /**
     * Nama primary key
     * 
     * @var string $primaryKey
     */
    public $primaryKey = 'id';

    /**
     * Id yang terakhir dimasukan ketika insert
     * 
     * @var string $primaryKey
     */
    public $insertId = null;

    /**
     * Fungsi yang pertamakali di jalankan
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->table)) $this->table = str_replace('M_', '', get_class($this));
    }

    /**
     * Select data
     * 
     * @param int $limit
     * @param int $offset
     * @return CI_DB_result::class query result
     */
    public function get(int $limit = null, int $offset = null)
    {
        $this->db->limit($limit);
        $this->db->offset($offset);
        $query = $this->db->get_compiled_select($this->table);

        return $this->execute($query);
    }

    /**
     * Ambil semua data
     * 
     * @param int $limit
     * @param int $offset
     * @return CI_DB_result::class query result
     */
    // public function all(int $limit = null, int $offset = null)
    // {
    //     $DB = $this->getDB();
    //     return $DB->get($this->table, $limit, $offset);
    // }
    public function all(int $limit = null, int $offset = null)
    {
        return $this->get($limit, $offset);
    }

    /**
     * Ambil data berdasarkan field (get_where)
     * 
     * @param array $where
     * @param int $limit
     * @param int $offset
     * @return CI_DB_result::class query result
     */
    // public function find(array $where, int $limit = null, int $offset = null)
    // {
    //     $DB = $this->getDB();
    //     return $DB->get_where($this->table, $where, $limit, $offset);
    // }
    public function find(array $where, int $limit = null, int $offset = null)
    {
        $this->db->where($where);
        $this->db->limit($limit);
        $this->db->offset($offset);
        $query = $this->db->get_compiled_select($this->table);

        return $this->execute($query);
    }

    /**
     * Menyimpan data
     * 
     * Menambahkan data atau merubah data yang ada
     * 
     * @param array $set datanya
     * @param int $where kondisinya(jika update)
     * @return bool
     */
    // public function save(array $set, array $where = [])
    // {
    //     $DB = $this->getDB();
    //     if (empty($where))
    //         return $DB->insert($this->table, $set);
    //     else
    //         return $DB->update($this->table, $set, $where);
    // }
    public function save(array $set, array $where = [])
    {
        $this->db->set($set);
        if (empty($where)) {
            $query = $this->db->get_compiled_insert($this->table);
        } else {
            $this->db->where($where);
            $query = $this->db->get_compiled_update($this->table);
        }

        return $this->execute($query);
    }

    /**
     * Menghapus data
     * 
     * @param array $where
     * @return bool
     */
    // public function destroy(array $where)
    // {
    //     $DB = &$this->getDB();
    //     return $DB->delete($this->table, $where);
    // }
    public function destroy(array $where)
    {
        $this->db->where($where);
        $query = $this->db->get_compiled_delete($this->table);

        return $this->execute($query);
    }

    public function insert_batch($set = NULL, $escape = NULL, $batch_size = 100)
    {
        if (empty($set)) {
            return null;
        }

        $DB = $this->getDB();
        return $DB->insert_batch($this->table, $set, $escape, $batch_size);
    }

    public function update_batch($set = NULL, $index = NULL, $batch_size = 100)
    {
        if (empty($set)) {
            return null;
        }

        $DB = $this->getDB();
        return $DB->update_batch($this->table, $set, $index, $batch_size);
    }

    private function execute($query)
    {
        if (isset($this->alternate_db_con)) {
            $DB = $this->load->database($this->alternate_db_con, TRUE, TRUE);
            $result = $DB->query($query);
            $this->insertId = $DB->insert_id();
            return $result;
        }
        return $this->db->query($query);
    }

    private function getDB()
    {
        if (isset($this->alternate_db_con)) {
            return $this->load->database($this->alternate_db_con, TRUE, TRUE);
        }
        return $this->db;
    }
}
