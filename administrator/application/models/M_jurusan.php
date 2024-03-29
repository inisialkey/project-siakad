<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

class M_jurusan extends CI_Model {
    public $table = 'jurusan';
    public $id    = 'id_jurusan';
    public $order = 'DESC';

    public function __construct()
    {
        parent::__construct();
    }

    function json() {
        $this->datatables->select('id_jurusan,kode_jurusan,nama_jurusan');
        $this->datatables->from('jurusan');
        $this->datatables->add_column('action', anchor(site_url('jurusan/update/$1'),'<button type="button" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>')."  ".anchor(site_url('jurusan/delete/$1'),'<button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_jurusan');
        return $this->datatables->generate();
    }

    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    //menampilkan jumlah data
    function total_rows($q= NULL)
    {
        $this->db->like('id_jurusan', $q);
        $this->db->or_like('kode_jurusan', $q);
        $this->db->or_like('nama_jurusan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    //menampilkan data dengan jumlah limit
    function get_limit_data($limit, $start = 0, $q=NULL)
    {
        $this->db->like('id_jurusan', $q);
        $this->db->or_like('kode_jurusan', $q);
        $this->db->or_like('nama_jurusan', $q);
        $this->db->limit($limit, $start);
        return $this->db->where($this->table)->result();
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }


}

?>
