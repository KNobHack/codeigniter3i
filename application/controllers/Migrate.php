<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Migration class
 * 
 * Class untuk migrasi database
 */
class Migrate extends CI_Controller
{
    /**
     * Nama database
     */
    private $database;

    /**
     * Fungsi yang pertamakali di panggil
     */
    public function __construct()
    {
        // Khusus dijalankan lewat CLI (tidak boleh lewat HTTP)
        if (!is_cli()) show_404();

        parent::__construct();
        $this->load->library('migration');

        $this->database = $this->config->item('database');;
    }

    /**
     * Menjalankan migrasi
     * 
     * @param int $version versi yang ingin di jalankan
     */
    public function run(int $version = null)
    {
        // Jangan reset database jika sedang dalam fase production
        // if (ENVIRONMENT === 'production' && $version === 0) show_error('Sedang dalam fase production!!! tidak bisa me-reset database');

        if ($version === null)
            $return_value = $this->migration->latest();
        else
            $return_value = $this->migration->version($version);

        if ($return_value === false)
            show_error($this->migration->error_string());
        elseif ($return_value === true)
            echo "\033[0;34mMigrasi sudah ada versi terbaru\033[0m\n";
        elseif ($return_value === '0')
            echo "\033[0;31mDatabse di reset\033[0m\n";
        else
            echo "\033[0;34mMigrasi di update ke versi {$return_value}\033[0m\n";
    }

    /**
     * Reset database seperti semula
     * 
     * @param string $hard hard
     */
    public function reset(string $hard = null)
    {
        // Jangan reset database jika sedang dalam fase production
        // if (ENVIRONMENT === 'production') show_error('Sedang dalam fase production!!! tidak bisa me-reset database');

        if (strtolower($hard) === 'hard') {
            // Hapus database lalu buat lagi
            if ($this->dbforge->drop_database($this->database)) echo "\033[0;31mDatabase {$this->database} di hapus\033[0m\n";
            if ($this->dbforge->create_database($this->database)) echo "\033[0;32mDatabse {$this->database} dibuat\033[0m\n";
        } else {
            // reset dari migrasi
            $this->run(0);
        }
    }

    /**
     * Buat database dari awal
     */
    public function refresh()
    {
        $this->run(0);
        $this->run();
    }

    /**
     * Alternatif
     */
    public function index()
    {
        $this->run();
    }

    /**
     * Lihat file file migrasi yang tersedia
     */
    public function migrations()
    {
        $migrations = $this->migration->find_migrations();

        if (empty($migrations)) {
            echo "\033[0;35mBelum ada migrasi tersedia\033[0m\n";
        } else {
            echo "\033[0;36mBerikut versi-versi migrasi yang tersedia\033[0m\n";
            foreach ($migrations as $migration)
                echo "\033[0;36m- {$migration}\033[0m\n";
        }
    }
}
